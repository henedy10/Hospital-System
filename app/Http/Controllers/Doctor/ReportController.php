<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Mock data for reports
        $stats = [
            ['label' => 'Total Reports', 'value' => 128, 'change' => '+12%', 'icon' => 'fas fa-file-medical', 'color' => 'bg-teal'],
            ['label' => 'Pending Review', 'value' => 14, 'change' => '-5%', 'icon' => 'fas fa-clock', 'color' => 'bg-amber'],
            ['label' => 'Diagnostic Accuracy', 'value' => '94%', 'change' => '+2%', 'icon' => 'fas fa-chart-line', 'color' => 'bg-rose'],
        ];

        $reports = [
            ['id' => 'R-1024', 'name' => 'Routine Checkup - Ahmed Mohamed', 'date' => '2026-02-28', 'category' => 'General Checkup', 'status' => 'Ready', 'status_type' => 'success'],
            ['id' => 'R-1025', 'name' => 'Complete Blood Count - Sarah Ali', 'date' => '2026-02-27', 'category' => 'Laboratory', 'status' => 'Processing', 'status_type' => 'warning'],
            ['id' => 'R-1026', 'name' => 'MRI Scan - Mahmoud Hassan', 'date' => '2026-02-26', 'category' => 'Radiology', 'status' => 'Review', 'status_type' => 'info'],
            ['id' => 'R-1027', 'name' => 'Cardiac Surgery Report - Lily Youssef', 'date' => '2026-02-25', 'category' => 'Surgery', 'status' => 'Ready', 'status_type' => 'success'],
            ['id' => 'R-1028', 'name' => 'Orthopedic Exam - Omar Khaled', 'date' => '2026-02-24', 'category' => 'Orthopedics', 'status' => 'Cancelled', 'status_type' => 'danger'],
        ];

        return view('doctor.reports.index', compact('stats', 'reports'));
    }

    public function show($id)
    {
        // Detailed mock data for a "Best Case" report
        $report = [
            'id' => $id,
            'name' => 'Comprehensive Routine Checkup Report',
            'date' => '2026-02-28',
            'category' => 'Cardiology',
            'department_en' => 'Cardiology and Vascular Department',
            'status' => 'Ready for final review',
            'patient' => [
                'name' => 'Ahmed Mohamed Ali',
                'id' => 'P-99201',
                'age' => 45,
                'weight' => '82 kg',
                'blood_type' => 'O+',
                'avatar' => 'https://ui-avatars.com/api/?name=Ahmed+Mohamed&background=0D9488&color=fff'
            ],
            'diagnosis' => 'Suspected mild hypertension with regular heart rhythm. Routine follow-up and reduced sodium intake are recommended.',
            'vitals' => [
                ['label' => 'Blood Pressure', 'value' => '135/85', 'status' => 'High Normal'],
                ['label' => 'Heart Rate', 'value' => '72 bpm', 'status' => 'Regular'],
                ['label' => 'Temperature', 'value' => '37.1 °C', 'status' => 'Normal'],
            ],
            'clinical_notes' => 'The patient suffers from persistent work stress and frequent migraines. Tests performed last week showed acceptable results except for a slight increase in cholesterol.',
            'treatment_plan' => [
                'One Amlodipine tablet (5mg) in the evening.',
                '30 minutes of walking daily.',
                'Repeat tests after two weeks.'
            ]
        ];

        return view('doctor.reports.show', compact('report'));
    }
}
