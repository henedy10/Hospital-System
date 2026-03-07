<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Vital;
use Carbon\Carbon;

class PatientDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patient = User::where('role', 'patient')->first();

        if (!$patient) {
            $patient = User::create([
                'name' => 'John Doe',
                'email' => 'patient@example.com',
                'phone' => '1234567890',
                'password' => bcrypt('password'),
                'role' => 'patient',
                'dob' => '1995-05-15',
                'address' => '123 Medical Lane, Health City, HC 12345',
                'emergency_contact_name' => 'Robert Johnson',
                'emergency_contact_phone' => '+1 (555) 987-6543',
                'emergency_contact_relationship' => 'Spouse',
                'blood_type' => 'O+',
                'allergies' => 'Peanuts, Penicillin',
                'insurance_provider' => 'Blue Shield Health',
                'insurance_member_id' => 'BS-992031',
                'insurance_plan' => 'Premium Plus',
            ]);
        }

        // Seed Appointments
        Appointment::create([
            'user_id' => $patient->id,
            'doctor_name' => 'Dr. Smith',
            'appointment_date' => Carbon::now()->addDays(2),
            'appointment_time' => '10:00:00',
            'reason' => 'Monthly Checkup',
            'status' => 'upcoming',
        ]);

        Appointment::create([
            'user_id' => $patient->id,
            'doctor_name' => 'Dr. Jones',
            'appointment_date' => Carbon::now()->subDays(5),
            'appointment_time' => '14:30:00',
            'reason' => 'Eye Exam',
            'status' => 'completed',
        ]);

        // Seed Medical History
        MedicalHistory::create([
            'user_id' => $patient->id,
            'condition' => 'Hypertension',
            'diagnosis_date' => '2023-05-15',
            'treatment' => 'Lisinopril 10mg daily',
            'doctor_name' => 'Dr. Smith',
        ]);

        MedicalHistory::create([
            'user_id' => $patient->id,
            'condition' => 'Type 2 Diabetes',
            'diagnosis_date' => '2022-10-20',
            'treatment' => 'Metformin 500mg twice daily, Diet control',
            'doctor_name' => 'Dr. Smith',
        ]);

        // Seed Vitals
        Vital::create([
            'user_id' => $patient->id,
            'blood_pressure' => '120/80',
            'heart_rate' => '72',
            'temperature' => '98.6',
            'respiratory_rate' => '16',
            'created_at' => Carbon::now()->subHours(2),
        ]);
    }
}
