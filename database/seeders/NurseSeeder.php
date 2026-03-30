<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Patient;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class NurseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a Nurse User if not exists
        $nurse = User::updateOrCreate(
            ['email' => 'joy@hospital.com'],
            [
                'name' => 'Nurse Joy',
                'password' => Hash::make('password'),
                'role' => User::ROLE_NURSE,
                'phone' => '123-456-7890',
                'department' => 'Emergency Room',
                'shift' => 'Morning (08:00 - 16:00)',
            ]
        );

        // Assign some existing patients to this nurse
        $patients = Patient::take(5)->get();
        foreach ($patients as $patient) {
            $patient->update(['nurse_id' => $nurse->id]);
        }

        // Create some tasks for today
        Task::create([
            'user_id' => $nurse->id,
            'patient_id' => $patients->first()->id ?? null,
            'title' => 'Morning Medication Round',
            'due_at' => Carbon::today()->setHour(9)->setMinute(0),
            'status' => 'completed',
            'category' => 'Clinical',
            'priority' => 'High',
        ]);

        Task::create([
            'user_id' => $nurse->id,
            'patient_id' => $patients->get(1)->id ?? null,
            'title' => 'Wound Care - Room 104',
            'due_at' => Carbon::today()->setHour(10)->setMinute(30),
            'status' => 'pending',
            'category' => 'Clinical',
            'priority' => 'Medium',
        ]);

        Task::create([
            'user_id' => $nurse->id,
            'title' => 'Shift Handover',
            'due_at' => Carbon::today()->setHour(8)->setMinute(0),
            'status' => 'completed',
            'category' => 'Administrative',
            'priority' => 'High',
        ]);

        // Create an upcoming task
        Task::create([
            'user_id' => $nurse->id,
            'title' => 'Afternoon Vitals Check',
            'due_at' => Carbon::tomorrow()->setHour(14)->setMinute(0),
            'status' => 'pending',
            'category' => 'Clinical',
            'priority' => 'Medium',
        ]);
    }
}
