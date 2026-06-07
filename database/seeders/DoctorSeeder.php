<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name'             => 'Dr. Amina Hassan',
                'email'            => 'amina@hospital.com',
                'phone'            => '+1-800-555-1001',
                'specialty'        => 'Radiology · MRI Specialist · Neurology',
                'experience_years' => 14,
                'bio'              => 'Dr. Amina Hassan is a board-certified radiologist specialising in MRI neuroimaging. With over 14 years of clinical experience, she has led diagnostic imaging departments and published peer-reviewed research on brain tumour detection using advanced MRI protocols.',
            ],
            [
                'name'             => 'Dr. Omar Khalil',
                'email'            => 'omar@hospital.com',
                'phone'            => '+1-800-555-1002',
                'specialty'        => 'Cardiology · ECG · Stress Testing',
                'experience_years' => 18,
                'bio'              => 'Dr. Omar Khalil is a senior cardiologist with expertise in interventional cardiology and non-invasive cardiac stress testing. He has performed over 3,000 cardiac catheterisations and is a fellow of the American College of Cardiology.',
            ],
            [
                'name'             => 'Dr. Sarah Connor',
                'email'            => 'sarah@hospital.com',
                'phone'            => '+1-800-555-1003',
                'specialty'        => 'Pathology · Hematology · Biochemistry',
                'experience_years' => 11,
                'bio'              => 'Dr. Sarah Connor is a clinical pathologist focused on haematological malignancies and complex biochemical panels. She heads the hospital laboratory quality-assurance programme and trains junior pathologists.',
            ],
            [
                'name'             => 'Dr. Michael Barnes',
                'email'            => 'michael@hospital.com',
                'phone'            => '+1-800-555-1004',
                'specialty'        => 'General Medicine · Internal Medicine',
                'experience_years' => 20,
                'bio'              => 'Dr. Michael Barnes is an experienced internist who manages complex multi-system diseases. He is the department head of General Medicine and has a special interest in preventive care and patient education.',
            ],
            [
                'name'             => 'Dr. Nora El-Sayed',
                'email'            => 'nora@hospital.com',
                'phone'            => '+1-800-555-1005',
                'specialty'        => 'Endocrinology · Diabetes Management · Thyroid',
                'experience_years' => 9,
                'bio'              => 'Dr. Nora El-Sayed specialises in metabolic and endocrine disorders including diabetes, thyroid conditions, and polycystic ovary syndrome. She holds a PhD in endocrine physiology from Johns Hopkins University.',
            ],
            [
                'name'             => 'Dr. Chen Wei',
                'email'            => 'chen@hospital.com',
                'phone'            => '+1-800-555-1006',
                'specialty'        => 'Oncology · Chemotherapy · Palliative Care',
                'experience_years' => 16,
                'bio'              => 'Dr. Chen Wei is a consultant oncologist specialising in solid tumour management and palliative care pathways. He is an active clinical trial investigator and recipient of the National Cancer Research Excellence Award.',
            ],
            [
                'name'             => 'Dr. Priya Sharma',
                'email'            => 'priya@hospital.com',
                'phone'            => '+1-800-555-1007',
                'specialty'        => 'Pulmonology · Respiratory Medicine · Sleep Medicine',
                'experience_years' => 12,
                'bio'              => 'Dr. Priya Sharma is a pulmonologist who diagnoses and manages asthma, COPD, pulmonary fibrosis, and obstructive sleep apnea. She runs the hospital\'s sleep laboratory and is certified in bronchoscopy procedures.',
            ],
            [
                'name'             => 'Dr. James O\'Brien',
                'email'            => 'james.obrien@hospital.com',
                'phone'            => '+1-800-555-1008',
                'specialty'        => 'Nephrology · Kidney Disease · Dialysis',
                'experience_years' => 22,
                'bio'              => 'Dr. James O\'Brien is one of the most experienced nephrologists in the region, overseeing the hospital\'s haemodialysis unit. He specialises in chronic kidney disease staging, transplant nephrology, and hypertensive kidney complications.',
            ],
        ];

        foreach ($doctors as $docData) {
            $user = User::updateOrCreate(
                ['email' => $docData['email']],
                [
                    'name'        => $docData['name'],
                    'phone'       => $docData['phone'],
                    'password'    => Hash::make('password'),
                    'role'        => User::ROLE_DOCTOR,
                    'is_verified' => true,
                ]
            );

            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialty' => $docData['specialty'],
                    'bio'       => $docData['bio'],
                ]
            );
        }

        // Seed some available slots for each doctor for the next 7 days
        $doctors = Doctor::all();
        $times = ['09:00:00', '10:00:00', '11:00:00', '13:00:00', '14:00:00', '15:00:00'];
        foreach ($doctors as $doctor) {
            for ($i = 0; $i < 7; $i++) {
                $date = \Carbon\Carbon::now()->addDays($i)->toDateString();
                foreach ($times as $time) {
                    \App\Models\Appointment::create([
                        'doctor_id' => $doctor->id,
                        'patient_id' => null,
                        'appointment_date' => $date,
                        'appointment_time' => $time,
                        'status' => 'available',
                        'reason' => null
                    ]);
                }
            }
        }
    }
}
