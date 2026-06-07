<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nurse;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class NurseSeeder extends Seeder
{
    public function run(): void
    {
        $nurses = [
            [
                'name'       => 'Joy Martinez',
                'email'      => 'joy@hospital.com',
                'phone'      => '+1-800-555-2001',
                'department' => 'Emergency Room',
                'shift'      => 'Morning (08:00 - 16:00)',
                'speciality' => 'Trauma & Emergency Care',
                'bio'        => 'Experienced ER nurse with a calm and decisive approach in high-pressure situations.',
            ],
            [
                'name'       => 'Hannah Brooks',
                'email'      => 'hannah@hospital.com',
                'phone'      => '+1-800-555-2002',
                'department' => 'Cardiology Ward',
                'shift'      => 'Evening (16:00 - 00:00)',
                'speciality' => 'Cardiac Monitoring',
                'bio'        => 'Specialised in post-op cardiac care and ECG interpretation.',
            ],
            [
                'name'       => 'Tariq Bilal',
                'email'      => 'tariq@hospital.com',
                'phone'      => '+1-800-555-2003',
                'department' => 'ICU',
                'shift'      => 'Night (00:00 - 08:00)',
                'speciality' => 'Critical Care',
                'bio'        => 'ICU specialist nurse with extensive experience in ventilator management and sepsis protocols.',
            ],
            [
                'name'       => 'Lisa Park',
                'email'      => 'lisa@hospital.com',
                'phone'      => '+1-800-555-2004',
                'department' => 'Oncology Ward',
                'shift'      => 'Morning (08:00 - 16:00)',
                'speciality' => 'Oncology & Palliative Care',
                'bio'        => 'Compassionate oncology nurse skilled in chemotherapy administration and symptom management.',
            ],
        ];

        $createdNurses = [];

        foreach ($nurses as $nurseData) {
            $user = User::updateOrCreate(
                ['email' => $nurseData['email']],
                [
                    'name'        => $nurseData['name'],
                    'phone'       => $nurseData['phone'],
                    'password'    => Hash::make('password'),
                    'role'        => User::ROLE_NURSE,
                    'is_verified' => true,
                ]
            );

            $nurse = Nurse::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'department' => $nurseData['department'],
                    'shift'      => $nurseData['shift'],
                    'speciality' => $nurseData['speciality'],
                    'bio'        => $nurseData['bio'],
                ]
            );

            $createdNurses[] = ['user' => $user, 'nurse' => $nurse];
        }

        // Seed tasks for each nurse
        $taskTemplates = [
            [
                'title'       => 'Morning Medication Round',
                'status'      => 'completed',
                'category'    => 'Clinical',
                'priority'    => 'High',
                'offset_days' => 0,
                'hour'        => 9,
            ],
            [
                'title'       => 'Wound Care – Room 104',
                'status'      => 'pending',
                'category'    => 'Clinical',
                'priority'    => 'Medium',
                'offset_days' => 0,
                'hour'        => 10,
            ],
            [
                'title'       => 'Shift Handover Documentation',
                'status'      => 'completed',
                'category'    => 'Administrative',
                'priority'    => 'High',
                'offset_days' => 0,
                'hour'        => 8,
            ],
            [
                'title'       => 'Afternoon Vitals Check',
                'status'      => 'pending',
                'category'    => 'Clinical',
                'priority'    => 'Medium',
                'offset_days' => 1,
                'hour'        => 14,
            ],
            [
                'title'       => 'IV Line Change',
                'status'      => 'pending',
                'category'    => 'Clinical',
                'priority'    => 'High',
                'offset_days' => 0,
                'hour'        => 11,
            ],
            [
                'title'       => 'Patient Education – Discharge Instructions',
                'status'      => 'pending',
                'category'    => 'Administrative',
                'priority'    => 'Low',
                'offset_days' => 1,
                'hour'        => 15,
            ],
        ];

        foreach ($createdNurses as $entry) {
            $nurseUser = $entry['user'];
            foreach ($taskTemplates as $task) {
                $due = $task['offset_days'] === 0
                    ? Carbon::today()->setHour($task['hour'])->setMinute(0)
                    : Carbon::tomorrow()->setHour($task['hour'])->setMinute(0);

                Task::create([
                    'user_id'  => $nurseUser->id,
                    'title'    => $task['title'],
                    'due_at'   => $due,
                    'status'   => $task['status'],
                    'category' => $task['category'],
                    'priority' => $task['priority'],
                ]);
            }
        }
    }
}
