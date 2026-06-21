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
            // ── CARDIOLOGY ────────────────────────────────────────────────────
            [
                'name' => 'Dr. Omar Khalil',
                'email' => 'omar@hospital.com',
                'phone' => '+1-800-555-1002',
                'specialty' => 'Cardiology',
                'experience_years' => 18,
                'bio' => 'Senior cardiologist with expertise in interventional cardiology and cardiac stress-testing. Fellow of the American College of Cardiology.',
            ],
            [
                'name' => 'Dr. Layla Mansour',
                'email' => 'layla.mansour@hospital.com',
                'phone' => '+1-800-555-1020',
                'specialty' => 'Cardiology',
                'experience_years' => 13,
                'bio' => 'Specialist in heart failure management and echocardiography. Conducted landmark research on cardiac biomarkers.',
            ],

            // ── PULMONOLOGY ───────────────────────────────────────────────────
            [
                'name' => 'Dr. Priya Sharma',
                'email' => 'priya@hospital.com',
                'phone' => '+1-800-555-1007',
                'specialty' => 'Pulmonology',
                'experience_years' => 12,
                'bio' => 'Pulmonologist specialising in asthma, COPD, and sleep medicine. Certified in bronchoscopy; runs the hospital sleep laboratory.',
            ],
            [
                'name' => 'Dr. Ahmed Farouk',
                'email' => 'ahmed.farouk@hospital.com',
                'phone' => '+1-800-555-1021',
                'specialty' => 'Pulmonology',
                'experience_years' => 10,
                'bio' => 'Expert in respiratory infections, pulmonary fibrosis, and critical care ventilation. Active researcher in occupational lung diseases.',
            ],

            // ── NEUROLOGY ─────────────────────────────────────────────────────
            [
                'name' => 'Dr. Amina Hassan',
                'email' => 'amina@hospital.com',
                'phone' => '+1-800-555-1001',
                'specialty' => 'Neurology',
                'experience_years' => 14,
                'bio' => 'Board-certified neurologist specialising in migraine management, stroke care, and MRI neuroimaging with 14 years of clinical experience.',
            ],
            [
                'name' => 'Dr. Fatima Al-Rashid',
                'email' => 'fatima.rashid@hospital.com',
                'phone' => '+1-800-555-1022',
                'specialty' => 'Neurology',
                'experience_years' => 9,
                'bio' => 'Neurologist with focus on epilepsy, vertigo, and neurodegenerative disorders. Completed fellowship at Johns Hopkins neurology department.',
            ],

            // ── ENT ───────────────────────────────────────────────────────────
            [
                'name' => 'Dr. Khalid Ibrahim',
                'email' => 'khalid.ibrahim@hospital.com',
                'phone' => '+1-800-555-1023',
                'specialty' => 'ENT',
                'experience_years' => 11,
                'bio' => 'Ear, Nose & Throat specialist covering sinusitis, tonsillitis, ear infections, and head & neck surgery. Skilled in endoscopic sinus procedures.',
            ],
            [
                'name' => 'Dr. Sara El-Sayed',
                'email' => 'sara.elsayed@hospital.com',
                'phone' => '+1-800-555-1024',
                'specialty' => 'ENT',
                'experience_years' => 8,
                'bio' => 'ENT surgeon with expertise in pharyngitis, hearing disorders, and paediatric ENT. Known for minimally invasive tonsillectomy techniques.',
            ],

            // ── GASTROENTEROLOGY ──────────────────────────────────────────────
            [
                'name' => 'Dr. Yusuf Al-Amin',
                'email' => 'yusuf.alamin@hospital.com',
                'phone' => '+1-800-555-1025',
                'specialty' => 'Gastroenterology',
                'experience_years' => 15,
                'bio' => 'Gastroenterologist specialising in peptic ulcer disease, IBD, and colonoscopy. Published research on gut microbiome and digestive health.',
            ],
            [
                'name' => 'Dr. Hana Khoury',
                'email' => 'hana.khoury@hospital.com',
                'phone' => '+1-800-555-1026',
                'specialty' => 'Gastroenterology',
                'experience_years' => 7,
                'bio' => 'Expert in food poisoning, gastroenteritis, and liver diseases. Uses advanced endoscopic techniques for minimally invasive GI treatment.',
            ],

            // ── GENERAL MEDICINE / INTERNAL MEDICINE ─────────────────────────
            [
                'name' => 'Dr. Michael Barnes',
                'email' => 'michael@hospital.com',
                'phone' => '+1-800-555-1004',
                'specialty' => 'General Medicine',
                'experience_years' => 20,
                'bio' => 'Head of General Medicine, managing complex multi-system diseases with a focus on preventive care and patient education.',
            ],
            [
                'name' => 'Dr. Rania Hassan',
                'email' => 'rania.hassan@hospital.com',
                'phone' => '+1-800-555-1027',
                'specialty' => 'Internal Medicine',
                'experience_years' => 16,
                'bio' => 'Internist specialising in viral infections, typhoid, dengue fever, and multi-organ complications in critically ill patients.',
            ],

            // ── EMERGENCY MEDICINE ────────────────────────────────────────────
            [
                'name' => 'Dr. Kevin Osei',
                'email' => 'kevin.osei@hospital.com',
                'phone' => '+1-800-555-1028',
                'specialty' => 'Emergency Medicine',
                'experience_years' => 12,
                'bio' => 'Emergency physician with expertise in sepsis, polytrauma, and critical haemodynamic stabilisation. Runs the hospital rapid response team.',
            ],

            // ── PSYCHIATRY ────────────────────────────────────────────────────
            [
                'name' => 'Dr. Lena Fischer',
                'email' => 'lena.fischer@hospital.com',
                'phone' => '+1-800-555-1029',
                'specialty' => 'Psychiatry',
                'experience_years' => 10,
                'bio' => 'Psychiatrist with focus on anxiety disorders, depression, and cognitive-behavioural therapy. Trained at the Max Planck Institute of Psychiatry.',
            ],

            // ── HEMATOLOGY ────────────────────────────────────────────────────
            [
                'name' => 'Dr. Sarah Connor',
                'email' => 'sarah@hospital.com',
                'phone' => '+1-800-555-1003',
                'specialty' => 'Hematology',
                'experience_years' => 11,
                'bio' => 'Clinical haematologist focused on anaemia, haematological malignancies, and complex blood disorders. Heads the lab quality-assurance programme.',
            ],

            // ── ENDOCRINOLOGY ─────────────────────────────────────────────────
            [
                'name' => 'Dr. Nora El-Sayed',
                'email' => 'nora@hospital.com',
                'phone' => '+1-800-555-1005',
                'specialty' => 'Endocrinology',
                'experience_years' => 9,
                'bio' => 'Specialises in diabetes, thyroid conditions, and metabolic disorders. PhD in endocrine physiology from Johns Hopkins University.',
            ],

            // ── ONCOLOGY ─────────────────────────────────────────────────────
            [
                'name' => 'Dr. Chen Wei',
                'email' => 'chen@hospital.com',
                'phone' => '+1-800-555-1006',
                'specialty' => 'Oncology',
                'experience_years' => 16,
                'bio' => 'Consultant oncologist in solid tumour management and palliative care. Active clinical trial investigator; National Cancer Research Excellence Award recipient.',
            ],

            // ── NEPHROLOGY ────────────────────────────────────────────────────
            [
                'name' => 'Dr. James O\'Brien',
                'email' => 'james.obrien@hospital.com',
                'phone' => '+1-800-555-1008',
                'specialty' => 'Nephrology',
                'experience_years' => 22,
                'bio' => 'One of the most experienced nephrologists in the region. Oversees the haemodialysis unit and specialises in chronic kidney disease and transplant nephrology.',
            ],
        ];

        foreach ($doctors as $docData) {
            $user = User::updateOrCreate(
                ['email' => $docData['email']],
                [
                    'name' => $docData['name'],
                    'phone' => $docData['phone'],
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_DOCTOR,
                    'is_verified' => true,
                ]
            );

            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialty' => $docData['specialty'],
                    'bio' => $docData['bio'],
                ]
            );
        }

        // Seed available appointment slots for each doctor — next 7 days
        $allDoctors = Doctor::all();
        $times = ['09:00:00', '10:00:00', '11:00:00', '13:00:00', '14:00:00', '15:00:00', '16:00:00'];

        foreach ($allDoctors as $doctor) {
            for ($i = 0; $i < 7; $i++) {
                $date = \Carbon\Carbon::now()->addDays($i)->toDateString();
                foreach ($times as $time) {
                    \App\Models\Appointment::firstOrCreate([
                        'doctor_id' => $doctor->id,
                        'patient_id' => null,
                        'appointment_date' => $date,
                        'appointment_time' => $time,
                        'status' => 'available',
                    ], [
                        'reason' => null,
                    ]);
                }
            }
        }
    }
}
