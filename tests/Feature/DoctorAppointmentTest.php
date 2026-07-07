<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DoctorAppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_cancelling_appointment_submits_cancellation_webhook_with_suggested_appointments()
    {
        Http::fake([
            'https://finicky-unstuffed-rewrap.ngrok-free.dev/*' => Http::response([], 200)
        ]);

        // Create Doctor User and Doctor Profile
        $doctorUser = User::factory()->create([
            'role' => User::ROLE_DOCTOR,
            'name' => 'Dr. Bob Smith',
        ]);
        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'specialty' => 'Cardiology',
            'bio' => 'Cardiology Specialist',
        ]);

        // Create Patient User and Patient Profile
        $patientUser = User::factory()->create([
            'role' => User::ROLE_PATIENT,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'patient_id' => 'PAT-TEST-01',
            'gender' => 'Male',
        ]);

        // Create a booked appointment to cancel
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '10:00:00',
            'reason' => 'Checkup',
            'status' => 'upcoming',
        ]);

        // Create available future slots for the same doctor
        // 1. One today, slightly later than now
        $slot1 = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => null,
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '23:59:59', // very late today
            'status' => 'available',
        ]);

        // 2. One tomorrow
        $slot2 = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => null,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'appointment_time' => '09:00:00',
            'status' => 'available',
        ]);

        // 3. One day after tomorrow
        $slot3 = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => null,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '14:00:00',
            'status' => 'available',
        ]);

        // 4. One day after that (should not be in the top 3 nearest)
        $slot4 = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => null,
            'appointment_date' => now()->addDays(3)->toDateString(),
            'appointment_time' => '11:00:00',
            'status' => 'available',
        ]);

        // Cancel the appointment as the logged-in doctor
        $response = $this->actingAs($doctorUser)->patch(
            route('doctor.appointments.update-status', $appointment),
            ['status' => 'cancelled']
        );

        $response->assertStatus(302);

        // Assert appointment is cancelled
        $appointment->refresh();
        $this->assertEquals('cancelled', $appointment->status);

        // Assert Webhook was sent with the nearest 3 available slots
        Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($slot1, $slot2, $slot3) {
            $data = $request->data();

            // Should contain suggested_appointments
            if (!isset($data['suggested_appointments'])) {
                return false;
            }

            $suggested = $data['suggested_appointments'];

            // Assert exactly 3 slots are returned
            if (count($suggested) !== 3) {
                return false;
            }

            // Assert they are the correct 3 slots (slot1, slot2, slot3) in correct chronological order
            if (
                $suggested[0]['id'] !== $slot1->id ||
                $suggested[1]['id'] !== $slot2->id ||
                $suggested[2]['id'] !== $slot3->id
            ) {
                return false;
            }

            // Verify they contain the expected attributes
            return isset($suggested[0]['appointment_date']) && isset($suggested[0]['appointment_time']);
        });

        // Assert Notification was stored for the patient
        $notifications = $patientUser->notifications;
        $this->assertCount(1, $notifications);

        $notiData = $notifications->first()->data;
        $this->assertArrayHasKey('suggested_appointments', $notiData);
        $this->assertCount(3, $notiData['suggested_appointments']);
        $this->assertEquals($slot1->id, $notiData['suggested_appointments'][0]['id']);
        $this->assertEquals($slot2->id, $notiData['suggested_appointments'][1]['id']);
        $this->assertEquals($slot3->id, $notiData['suggested_appointments'][2]['id']);

        $this->assertStringContainsString('Nearest alternative slots', $notiData['message']);
        $this->assertStringContainsString($slot1->appointment_date, $notiData['message']);
    }
}
