<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MedicalHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_add_medical_history_record()
    {
        $doctor = User::factory()->create(['role' => User::ROLE_DOCTOR]);
        $patient = User::factory()->create(['role' => User::ROLE_PATIENT]);

        $response = $this->actingAs($doctor)->post(route('doctor.medical-history.store'), [
            'user_id' => $patient->id,
            'condition' => 'Flu',
            'diagnosis_date' => now()->toDateString(),
            'treatment' => 'Rest and fluids',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('medical_histories', [
            'user_id' => $patient->id,
            'condition' => 'Flu',
            'doctor_name' => $doctor->name,
        ]);
    }

    public function test_patient_can_view_medical_history()
    {
        $patient = User::factory()->create(['role' => User::ROLE_PATIENT]);
        MedicalHistory::factory()->create([
            'user_id' => $patient->id,
            'condition' => 'Asthma',
        ]);

        $response = $this->actingAs($patient)->get(route('patient.history'));

        $response->assertStatus(200);
        $response->assertSee('Asthma');
    }
}
