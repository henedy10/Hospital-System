<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        // Stats for the nurse patients page
        $stats = [
            ['label' => 'Total Patients', 'value' => '45', 'icon' => 'fas fa-users', 'color' => 'bg-teal'],
            ['label' => 'Critical Cases', 'value' => '3', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'bg-red'],
            ['label' => 'Pending Vitals', 'value' => '7', 'icon' => 'fas fa-heartbeat', 'color' => 'bg-amber'],
        ];

        // Mock data for patients under nurse care
        $patients = [
            [
                'id' => 1,
                'room' => '101-A',
                'name' => 'John Wick',
                'age' => 45,
                'gender' => 'Male',
                'condition' => 'Recovering from surgery',
                'last_vitals' => '10:30 AM',
                'next_dose' => '12:00 PM',
                'status' => 'Stable',
                'avatar' => 'https://ui-avatars.com/api/?name=John+Wick&background=0D9488&color=fff'
            ],
            [
                'id' => 2,
                'room' => '104-B',
                'name' => 'Sarah Connor',
                'age' => 38,
                'gender' => 'Female',
                'condition' => 'Post-partum observation',
                'last_vitals' => '09:45 AM',
                'next_dose' => '01:00 PM',
                'status' => 'Observing',
                'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Connor&background=0D9488&color=fff'
            ],
            [
                'id' => 3,
                'room' => '202',
                'name' => 'Peter Parker',
                'age' => 24,
                'gender' => 'Male',
                'condition' => 'Allergic reaction',
                'last_vitals' => '11:15 AM',
                'next_dose' => 'ASAP',
                'status' => 'Critical',
                'avatar' => 'https://ui-avatars.com/api/?name=Peter+Parker&background=0D9488&color=fff'
            ],
        ];

        return view('nurse.patients.index', compact('patients', 'stats'));
    }

    public function show($id)
    {
        // Detailed mock data for a patient from a nurse's perspective
        $patient = [
            'id' => $id,
            'room' => '101-A',
            'name' => 'John Wick',
            'age' => 45,
            'birth_date' => '1981-05-12',
            'gender' => 'Male',
            'blood_type' => 'O+',
            'allergies' => ['Latex', 'Penicillin'],
            'avatar' => 'https://ui-avatars.com/api/?name=John+Wick&background=0D9488&color=fff',
            'vitals_history' => [
                ['time' => '10:30 AM', 'bp' => '120/80', 'temp' => '37.1', 'pulse' => '72', 'oxygen' => '98%'],
                ['time' => '06:30 AM', 'bp' => '118/78', 'temp' => '36.9', 'pulse' => '68', 'oxygen' => '99%'],
                ['time' => '02:30 AM', 'bp' => '115/75', 'temp' => '37.0', 'pulse' => '65', 'oxygen' => '98%'],
            ],
            'medication_schedule' => [
                ['time' => '08:00 AM', 'med' => 'Paracetamol 500mg', 'status' => 'Administered', 'nurse' => 'Nurse Joy'],
                ['time' => '12:00 PM', 'med' => 'Ceftriaxone 1g', 'status' => 'Pending', 'nurse' => 'Current'],
                ['time' => '04:00 PM', 'med' => 'Paracetamol 500mg', 'status' => 'Upcoming', 'nurse' => 'Next Shift'],
            ]
        ];

        return view('nurse.patients.show', compact('patient'));
    }
}
