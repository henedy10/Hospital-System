<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use App\Models\Vital;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        // Get doctors and nurses created by other seeders
        $doctors = Doctor::with('user')->get();
        $nurses  = Nurse::with('user')->get();

        $patientsData = [
            [
                'user' => [
                    'name'     => 'James Carter',
                    'email'    => 'james.carter@patient.com',
                    'phone'    => '+1-202-555-0101',
                    'password' => Hash::make('password'),
                    'role'     => User::ROLE_PATIENT,
                    'is_verified' => true,
                ],
                'profile' => [
                    'patient_id'                    => 'PAT-0001',
                    'gender'                        => 'Male',
                    'dob'                           => '1985-03-14',
                    'address'                       => '12 Maple Street, Springfield, IL 62701',
                    'emergency_contact_name'        => 'Linda Carter',
                    'emergency_contact_phone'       => '+1-202-555-0102',
                    'emergency_contact_relationship'=> 'Spouse',
                    'blood_type'                    => 'O+',
                    'allergies'                     => ['Penicillin', 'Peanuts'],
                    'insurance_provider'            => 'Blue Shield Health',
                    'insurance_member_id'           => 'BSH-100221',
                    'insurance_plan'                => 'Premium Plus',
                    'weight'                        => 82.5,
                    'height'                        => 178.0,
                    'status'                        => 'Stable',
                ],
                'conditions' => [
                    ['condition' => 'Hypertension',    'date' => '2021-06-10', 'treatment' => 'Lisinopril 10 mg once daily'],
                    ['condition' => 'Type 2 Diabetes', 'date' => '2020-11-05', 'treatment' => 'Metformin 500 mg twice daily, low-carb diet'],
                ],
                'vitals' => [
                    ['bp' => '138/88', 'hr' => '76', 'temp' => '36.7', 'rr' => '16', 'spo2' => 97, 'days_ago' => 1],
                    ['bp' => '132/84', 'hr' => '72', 'temp' => '36.6', 'rr' => '15', 'spo2' => 98, 'days_ago' => 7],
                ],
                'prescriptions' => [
                    [
                        'notes' => 'Hypertension management – take with food',
                        'items' => [
                            ['medicine' => 'Lisinopril', 'dosage' => '10 mg', 'freq' => 1, 'dur' => 90, 'instructions' => 'Once daily in the morning'],
                            ['medicine' => 'Amlodipine', 'dosage' => '5 mg',  'freq' => 1, 'dur' => 90, 'instructions' => 'Once daily'],
                        ],
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Fatima Al-Rashid',
                    'email'    => 'fatima.alrashid@patient.com',
                    'phone'    => '+1-312-555-0201',
                    'password' => Hash::make('password'),
                    'role'     => User::ROLE_PATIENT,
                    'is_verified' => true,
                ],
                'profile' => [
                    'patient_id'                    => 'PAT-0002',
                    'gender'                        => 'Female',
                    'dob'                           => '1992-07-22',
                    'address'                       => '45 Olive Avenue, Chicago, IL 60601',
                    'emergency_contact_name'        => 'Khalid Al-Rashid',
                    'emergency_contact_phone'       => '+1-312-555-0202',
                    'emergency_contact_relationship'=> 'Brother',
                    'blood_type'                    => 'A+',
                    'allergies'                     => ['Sulfa drugs'],
                    'insurance_provider'            => 'Aetna Health',
                    'insurance_member_id'           => 'AET-203445',
                    'insurance_plan'                => 'Gold Plan',
                    'weight'                        => 60.0,
                    'height'                        => 163.0,
                    'status'                        => 'Stable',
                ],
                'conditions' => [
                    ['condition' => 'Migraine',        'date' => '2019-04-20', 'treatment' => 'Sumatriptan 50 mg as needed, avoid triggers'],
                    ['condition' => 'Iron Deficiency Anemia', 'date' => '2022-01-15', 'treatment' => 'Ferrous sulfate 325 mg daily with vitamin C'],
                ],
                'vitals' => [
                    ['bp' => '110/70', 'hr' => '68', 'temp' => '36.5', 'rr' => '14', 'spo2' => 99, 'days_ago' => 2],
                ],
                'prescriptions' => [
                    [
                        'notes' => 'Iron deficiency – take on empty stomach if tolerated',
                        'items' => [
                            ['medicine' => 'Ferrous Sulfate', 'dosage' => '325 mg', 'freq' => 1, 'dur' => 60, 'instructions' => 'Once daily with orange juice'],
                            ['medicine' => 'Vitamin C',       'dosage' => '500 mg',  'freq' => 1, 'dur' => 60, 'instructions' => 'Alongside iron tablet'],
                        ],
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'David Nguyen',
                    'email'    => 'david.nguyen@patient.com',
                    'phone'    => '+1-415-555-0301',
                    'password' => Hash::make('password'),
                    'role'     => User::ROLE_PATIENT,
                    'is_verified' => true,
                ],
                'profile' => [
                    'patient_id'                    => 'PAT-0003',
                    'gender'                        => 'Male',
                    'dob'                           => '1978-11-30',
                    'address'                       => '88 Redwood Blvd, San Francisco, CA 94102',
                    'emergency_contact_name'        => 'Amy Nguyen',
                    'emergency_contact_phone'       => '+1-415-555-0302',
                    'emergency_contact_relationship'=> 'Spouse',
                    'blood_type'                    => 'B-',
                    'allergies'                     => ['Aspirin', 'Latex'],
                    'insurance_provider'            => 'Kaiser Permanente',
                    'insurance_member_id'           => 'KP-887712',
                    'insurance_plan'                => 'HMO Basic',
                    'weight'                        => 90.2,
                    'height'                        => 175.0,
                    'status'                        => 'Under Observation',
                ],
                'conditions' => [
                    ['condition' => 'Coronary Artery Disease', 'date' => '2018-09-12', 'treatment' => 'Atorvastatin 40 mg nightly, cardiac rehab'],
                    ['condition' => 'Hyperlipidemia',          'date' => '2017-03-05', 'treatment' => 'Rosuvastatin 20 mg once daily, low-fat diet'],
                    ['condition' => 'Chronic Back Pain',       'date' => '2023-05-18', 'treatment' => 'Physical therapy, Naproxen 500 mg as needed'],
                ],
                'vitals' => [
                    ['bp' => '145/92', 'hr' => '80', 'temp' => '36.8', 'rr' => '18', 'spo2' => 96, 'days_ago' => 1],
                    ['bp' => '148/94', 'hr' => '84', 'temp' => '36.9', 'rr' => '19', 'spo2' => 95, 'days_ago' => 3],
                    ['bp' => '142/90', 'hr' => '78', 'temp' => '36.7', 'rr' => '17', 'spo2' => 97, 'days_ago' => 10],
                ],
                'prescriptions' => [
                    [
                        'notes' => 'Cardiac maintenance – do not skip doses',
                        'items' => [
                            ['medicine' => 'Atorvastatin',  'dosage' => '40 mg', 'freq' => 1, 'dur' => 90, 'instructions' => 'Once at bedtime'],
                            ['medicine' => 'Metoprolol',    'dosage' => '25 mg', 'freq' => 2, 'dur' => 90, 'instructions' => 'Twice daily with meals'],
                            ['medicine' => 'Clopidogrel',   'dosage' => '75 mg', 'freq' => 1, 'dur' => 90, 'instructions' => 'Once daily'],
                        ],
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Maria Gonzalez',
                    'email'    => 'maria.gonzalez@patient.com',
                    'phone'    => '+1-713-555-0401',
                    'password' => Hash::make('password'),
                    'role'     => User::ROLE_PATIENT,
                    'is_verified' => true,
                ],
                'profile' => [
                    'patient_id'                    => 'PAT-0004',
                    'gender'                        => 'Female',
                    'dob'                           => '2000-02-14',
                    'address'                       => '201 Sunset Drive, Houston, TX 77001',
                    'emergency_contact_name'        => 'Carlos Gonzalez',
                    'emergency_contact_phone'       => '+1-713-555-0402',
                    'emergency_contact_relationship'=> 'Father',
                    'blood_type'                    => 'AB+',
                    'allergies'                     => ['None'],
                    'insurance_provider'            => 'Cigna',
                    'insurance_member_id'           => 'CIG-550901',
                    'insurance_plan'                => 'Silver PPO',
                    'weight'                        => 55.0,
                    'height'                        => 160.0,
                    'status'                        => 'Stable',
                ],
                'conditions' => [
                    ['condition' => 'Asthma',     'date' => '2010-08-01', 'treatment' => 'Salbutamol inhaler as needed, Fluticasone daily'],
                    ['condition' => 'Anxiety Disorder', 'date' => '2021-03-10', 'treatment' => 'Sertraline 50 mg once daily, CBT sessions'],
                ],
                'vitals' => [
                    ['bp' => '112/72', 'hr' => '74', 'temp' => '36.4', 'rr' => '15', 'spo2' => 98, 'days_ago' => 5],
                ],
                'prescriptions' => [
                    [
                        'notes' => 'Asthma control plan – carry rescue inhaler at all times',
                        'items' => [
                            ['medicine' => 'Salbutamol Inhaler',  'dosage' => '100 mcg', 'freq' => 2, 'dur' => 30, 'instructions' => 'As needed for breathlessness'],
                            ['medicine' => 'Fluticasone Inhaler', 'dosage' => '250 mcg', 'freq' => 2, 'dur' => 90, 'instructions' => 'Twice daily, rinse mouth after'],
                        ],
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Robert Kim',
                    'email'    => 'robert.kim@patient.com',
                    'phone'    => '+1-206-555-0501',
                    'password' => Hash::make('password'),
                    'role'     => User::ROLE_PATIENT,
                    'is_verified' => true,
                ],
                'profile' => [
                    'patient_id'                    => 'PAT-0005',
                    'gender'                        => 'Male',
                    'dob'                           => '1965-09-08',
                    'address'                       => '330 Cedar Lane, Seattle, WA 98101',
                    'emergency_contact_name'        => 'Susan Kim',
                    'emergency_contact_phone'       => '+1-206-555-0502',
                    'emergency_contact_relationship'=> 'Spouse',
                    'blood_type'                    => 'A-',
                    'allergies'                     => ['Codeine', 'Shellfish'],
                    'insurance_provider'            => 'United Health',
                    'insurance_member_id'           => 'UH-774231',
                    'insurance_plan'                => 'Platinum Care',
                    'weight'                        => 78.0,
                    'height'                        => 172.0,
                    'status'                        => 'Critical',
                ],
                'conditions' => [
                    ['condition' => 'Chronic Kidney Disease Stage 3', 'date' => '2015-07-20', 'treatment' => 'Low-protein diet, Erythropoietin injections, nephrology follow-up'],
                    ['condition' => 'Gout',                           'date' => '2019-12-01', 'treatment' => 'Allopurinol 300 mg daily, avoid purine-rich foods'],
                    ['condition' => 'Atrial Fibrillation',            'date' => '2022-04-14', 'treatment' => 'Warfarin 5 mg daily, INR monitoring'],
                ],
                'vitals' => [
                    ['bp' => '155/98', 'hr' => '92', 'temp' => '37.1', 'rr' => '20', 'spo2' => 94, 'days_ago' => 0],
                    ['bp' => '160/100','hr' => '96', 'temp' => '37.3', 'rr' => '22', 'spo2' => 93, 'days_ago' => 1],
                ],
                'prescriptions' => [
                    [
                        'notes' => 'AFib & CKD management – strict adherence required',
                        'items' => [
                            ['medicine' => 'Warfarin',      'dosage' => '5 mg',   'freq' => 1, 'dur' => 30,  'instructions' => 'Once daily at same time, monitor INR'],
                            ['medicine' => 'Allopurinol',   'dosage' => '300 mg', 'freq' => 1, 'dur' => 90,  'instructions' => 'Once daily after meals'],
                            ['medicine' => 'Furosemide',    'dosage' => '40 mg',  'freq' => 1, 'dur' => 60,  'instructions' => 'Once daily in the morning'],
                        ],
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Emily Thompson',
                    'email'    => 'emily.thompson@patient.com',
                    'phone'    => '+1-617-555-0601',
                    'password' => Hash::make('password'),
                    'role'     => User::ROLE_PATIENT,
                    'is_verified' => true,
                ],
                'profile' => [
                    'patient_id'                    => 'PAT-0006',
                    'gender'                        => 'Female',
                    'dob'                           => '1990-05-25',
                    'address'                       => '77 Park Street, Boston, MA 02101',
                    'emergency_contact_name'        => 'Mark Thompson',
                    'emergency_contact_phone'       => '+1-617-555-0602',
                    'emergency_contact_relationship'=> 'Husband',
                    'blood_type'                    => 'B+',
                    'allergies'                     => ['Amoxicillin'],
                    'insurance_provider'            => 'Harvard Pilgrim',
                    'insurance_member_id'           => 'HP-334411',
                    'insurance_plan'                => 'Choice Plus',
                    'weight'                        => 63.0,
                    'height'                        => 167.0,
                    'status'                        => 'Stable',
                ],
                'conditions' => [
                    ['condition' => 'Hypothyroidism',  'date' => '2016-02-28', 'treatment' => 'Levothyroxine 75 mcg daily, TSH monitoring every 6 months'],
                    ['condition' => 'GERD',            'date' => '2020-08-12', 'treatment' => 'Omeprazole 20 mg before breakfast, dietary modifications'],
                ],
                'vitals' => [
                    ['bp' => '118/76', 'hr' => '70', 'temp' => '36.6', 'rr' => '15', 'spo2' => 99, 'days_ago' => 4],
                ],
                'prescriptions' => [
                    [
                        'notes' => 'Thyroid & GERD management',
                        'items' => [
                            ['medicine' => 'Levothyroxine', 'dosage' => '75 mcg', 'freq' => 1, 'dur' => 90, 'instructions' => 'Once daily on empty stomach, 30 min before breakfast'],
                            ['medicine' => 'Omeprazole',    'dosage' => '20 mg',  'freq' => 1, 'dur' => 30, 'instructions' => 'Once daily before breakfast'],
                        ],
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Ahmed Youssef',
                    'email'    => 'ahmed.youssef@patient.com',
                    'phone'    => '+1-305-555-0701',
                    'password' => Hash::make('password'),
                    'role'     => User::ROLE_PATIENT,
                    'is_verified' => true,
                ],
                'profile' => [
                    'patient_id'                    => 'PAT-0007',
                    'gender'                        => 'Male',
                    'dob'                           => '1955-12-03',
                    'address'                       => '19 Palm Avenue, Miami, FL 33101',
                    'emergency_contact_name'        => 'Nadia Youssef',
                    'emergency_contact_phone'       => '+1-305-555-0702',
                    'emergency_contact_relationship'=> 'Daughter',
                    'blood_type'                    => 'O-',
                    'allergies'                     => ['Ibuprofen', 'Contrast dye'],
                    'insurance_provider'            => 'Medicare',
                    'insurance_member_id'           => 'MED-445567',
                    'insurance_plan'                => 'Medicare Advantage',
                    'weight'                        => 85.0,
                    'height'                        => 170.0,
                    'status'                        => 'Under Observation',
                ],
                'conditions' => [
                    ['condition' => 'Prostate Cancer (Stage 2)',  'date' => '2020-06-15', 'treatment' => 'Radiation therapy completed, Bicalutamide 50 mg daily, PSA monitoring'],
                    ['condition' => 'Osteoarthritis (bilateral knee)', 'date' => '2018-03-22', 'treatment' => 'Paracetamol 1g three times daily, physiotherapy'],
                    ['condition' => 'Benign Prostatic Hyperplasia',   'date' => '2017-11-10', 'treatment' => 'Tamsulosin 0.4 mg once daily at bedtime'],
                ],
                'vitals' => [
                    ['bp' => '125/80', 'hr' => '66', 'temp' => '36.9', 'rr' => '16', 'spo2' => 97, 'days_ago' => 2],
                    ['bp' => '130/82', 'hr' => '68', 'temp' => '37.0', 'rr' => '16', 'spo2' => 96, 'days_ago' => 9],
                ],
                'prescriptions' => [
                    [
                        'notes' => 'Oncology maintenance – continue until next review',
                        'items' => [
                            ['medicine' => 'Bicalutamide', 'dosage' => '50 mg', 'freq' => 1, 'dur' => 90, 'instructions' => 'Once daily with or without food'],
                            ['medicine' => 'Tamsulosin',   'dosage' => '0.4 mg','freq' => 1, 'dur' => 90, 'instructions' => 'Once daily 30 min after evening meal'],
                            ['medicine' => 'Paracetamol',  'dosage' => '1000 mg','freq' => 3, 'dur' => 30, 'instructions' => 'Three times daily, do not exceed 4g/day'],
                        ],
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Sophia Williams',
                    'email'    => 'sophia.williams@patient.com',
                    'phone'    => '+1-404-555-0801',
                    'password' => Hash::make('password'),
                    'role'     => User::ROLE_PATIENT,
                    'is_verified' => true,
                ],
                'profile' => [
                    'patient_id'                    => 'PAT-0008',
                    'gender'                        => 'Female',
                    'dob'                           => '2005-09-17',
                    'address'                       => '62 Peachtree Road, Atlanta, GA 30301',
                    'emergency_contact_name'        => 'Diana Williams',
                    'emergency_contact_phone'       => '+1-404-555-0802',
                    'emergency_contact_relationship'=> 'Mother',
                    'blood_type'                    => 'A+',
                    'allergies'                     => ['None'],
                    'insurance_provider'            => 'Humana',
                    'insurance_member_id'           => 'HUM-991122',
                    'insurance_plan'                => 'Student Health',
                    'weight'                        => 52.0,
                    'height'                        => 162.0,
                    'status'                        => 'Stable',
                ],
                'conditions' => [
                    ['condition' => 'Juvenile Idiopathic Arthritis', 'date' => '2014-04-10', 'treatment' => 'Methotrexate 10 mg weekly, folic acid supplementation'],
                    ['condition' => 'Vitamin D Deficiency',          'date' => '2023-01-05', 'treatment' => 'Cholecalciferol 2000 IU daily'],
                ],
                'vitals' => [
                    ['bp' => '108/68', 'hr' => '72', 'temp' => '36.4', 'rr' => '14', 'spo2' => 99, 'days_ago' => 6],
                ],
                'prescriptions' => [
                    [
                        'notes' => 'Autoimmune management – monthly blood tests required',
                        'items' => [
                            ['medicine' => 'Methotrexate',    'dosage' => '10 mg',   'freq' => 1, 'dur' => 90, 'instructions' => 'Once weekly on the same day'],
                            ['medicine' => 'Folic Acid',      'dosage' => '5 mg',    'freq' => 1, 'dur' => 90, 'instructions' => 'Once daily except methotrexate day'],
                            ['medicine' => 'Cholecalciferol', 'dosage' => '2000 IU', 'freq' => 1, 'dur' => 90, 'instructions' => 'Once daily with food'],
                        ],
                    ],
                ],
            ],
        ];

        $doctorsList   = $doctors->values();
        $nursesList    = $nurses->values();
        $doctorCount   = $doctorsList->count();
        $nurseCount    = $nursesList->count();

        foreach ($patientsData as $index => $data) {
            // Create / update the user
            $user = User::updateOrCreate(
                ['email' => $data['user']['email']],
                $data['user']
            );

            // Assign to a nurse (round-robin)
            $nurseUserId = $nurseCount > 0
                ? $nursesList[$index % $nurseCount]->user_id
                : null;

            // Create / update the patient profile
            $profile              = $data['profile'];
            $profile['user_id']   = $user->id;
            $profile['nurse_id']  = $nurseUserId;

            $patient = Patient::updateOrCreate(
                ['user_id' => $user->id],
                $profile
            );

            // Assign a doctor (round-robin)
            $doctor = $doctorCount > 0 ? $doctorsList[$index % $doctorCount] : null;

            // Medical histories
            foreach ($data['conditions'] as $cond) {
                MedicalHistory::firstOrCreate(
                    [
                        'patient_id' => $patient->id,
                        'condition'  => $cond['condition'],
                    ],
                    [
                        'doctor_id'       => $doctor?->id ?? $doctorsList->first()?->id,
                        'diagnosis_date'  => $cond['date'],
                        'treatment'       => $cond['treatment'],
                    ]
                );
            }

            // Vitals
            foreach ($data['vitals'] as $v) {
                $nurseModel = $nurseCount > 0 ? $nursesList[$index % $nurseCount] : null;
                Vital::create([
                    'user_id'          => $user->id,
                    'blood_pressure'   => $v['bp'],
                    'heart_rate'       => $v['hr'],
                    'temperature'      => $v['temp'],
                    'respiratory_rate' => $v['rr'],
                    'spo2'             => $v['spo2'],
                    'recorded_by'      => $nurseModel?->id,
                    'created_at'       => Carbon::now()->subDays($v['days_ago']),
                    'updated_at'       => Carbon::now()->subDays($v['days_ago']),
                ]);
            }

            // Appointments (2 per patient: 1 past, 1 upcoming)
            if ($doctor) {
                Appointment::create([
                    'patient_id'       => $patient->id,
                    'doctor_id'        => $doctor->id,
                    'appointment_date' => Carbon::now()->subDays(rand(10, 40))->toDateString(),
                    'appointment_time' => '09:00:00',
                    'reason'           => 'Follow-up: ' . ($data['conditions'][0]['condition'] ?? 'General Checkup'),
                    'status'           => 'completed',
                ]);

                Appointment::create([
                    'patient_id'       => $patient->id,
                    'doctor_id'        => $doctor->id,
                    'appointment_date' => Carbon::now()->addDays(rand(5, 20))->toDateString(),
                    'appointment_time' => '10:30:00',
                    'reason'           => 'Routine checkup & medication review',
                    'status'           => 'upcoming',
                ]);
            }

            // Prescriptions
            if ($doctor) {
                foreach ($data['prescriptions'] as $rx) {
                    $prescription = Prescription::create([
                        'patient_id' => $patient->id,
                        'doctor_id'  => $doctor->id,
                        'notes'      => $rx['notes'],
                    ]);

                    foreach ($rx['items'] as $item) {
                        PrescriptionItem::create([
                            'prescription_id' => $prescription->id,
                            'medicine_name'   => $item['medicine'],
                            'dosage'          => $item['dosage'],
                            'frequency'       => $item['freq'],
                            'duration'        => $item['dur'],
                            'instructions'    => $item['instructions'],
                        ]);
                    }
                }
            }
        }
    }
}
