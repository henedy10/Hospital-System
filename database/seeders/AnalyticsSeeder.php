<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure we have some doctors and patients
        $doctors = Doctor::all();
        if ($doctors->isEmpty()) {
            $user = User::factory()->create(['role' => 'doctor']);
            $doctors = collect([Doctor::create(['user_id' => $user->id, 'specialty' => 'General Medicine'])]);
        }

        $patients = Patient::all();
        if ($patients->isEmpty()) {
            $user = User::factory()->create(['role' => 'patient']);
            $patients = collect([Patient::create(['user_id' => $user->id, 'patient_id' => 'P-001'])]);
        }

        $diseases = ['Flu', 'Cold', 'Pneumonia', 'Migraine', 'Diabetes', 'Hypertension', 'Gastroenteritis', 'Asthma'];

        // 2. Clear existing data to avoid duplicates for this demo seeder
        DB::table('diagnoses')->truncate();
        DB::table('appointments')->truncate();

        $now = Carbon::now();

        // 3. Generate data for the last 6 months
        for ($i = 0; $i < 6; $i++) {
            $month = $now->copy()->subMonths($i);
            $daysInMonth = $month->daysInMonth;
            
            // Random number of patients per month (increasing over time to show growth)
            $patientCount = rand(50, 80) + (5 - $i) * 15; 

            for ($j = 0; $j < $patientCount; $j++) {
                $day = rand(1, $daysInMonth);
                $hour = rand(8, 20); // Mostly between 8 AM and 8 PM
                $minute = rand(0, 59);
                
                $date = $month->copy()->day($day);
                
                $appointment = Appointment::create([
                    'patient_id' => $patients->random()->id,
                    'doctor_id' => $doctors->random()->id,
                    'appointment_date' => $date->toDateString(),
                    'appointment_time' => sprintf('%02d:%02d:00', $hour, $minute),
                    'reason' => 'Routine Checkup',
                    'status' => 'completed',
                    'created_at' => $date->setTime($hour, $minute),
                ]);

                // Create a diagnosis for most appointments
                if (rand(1, 10) > 2) {
                    Diagnosis::create([
                        'patient_id' => $appointment->patient_id,
                        'doctor_id' => $appointment->doctor_id,
                        'disease_name' => $diseases[array_rand($diseases)],
                        'created_at' => $date,
                    ]);
                }
            }
        }
    }
}
