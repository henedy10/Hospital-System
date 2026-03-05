<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        // Stats for the patients page
        $stats = [
            ['label' => 'إجمالي المرضى', 'value' => '1,280', 'icon' => 'fas fa-users', 'color' => 'bg-teal'],
            ['label' => 'مرضى جدد (هذا الشهر)', 'value' => '42', 'icon' => 'fas fa-user-plus', 'color' => 'bg-sky'],
            ['label' => 'حالات حرجة', 'value' => '8', 'icon' => 'fas fa-exclamation-circle', 'color' => 'bg-red'],
        ];

        // Mock data for patients
        $patients = [
            [
                'id' => 1,
                'name' => 'أحمد محمد',
                'age' => 45,
                'gender' => 'ذكر',
                'blood_type' => 'O+',
                'last_visit' => '2026-02-20',
                'condition' => 'ارتفاع ضغط الدم',
                'avatar' => 'https://ui-avatars.com/api/?name=Ahmed+Mohamed&background=0D9488&color=fff'
            ],
            [
                'id' => 2,
                'name' => 'سارة علي',
                'age' => 32,
                'gender' => 'أنثى',
                'blood_type' => 'AB-',
                'last_visit' => '2026-02-25',
                'condition' => 'سكري النوع الثاني',
                'avatar' => 'https://ui-avatars.com/api/?name=Sara+Ali&background=0D9488&color=fff'
            ],
            [
                'id' => 3,
                'name' => 'محمود حسن',
                'age' => 28,
                'gender' => 'ذكر',
                'blood_type' => 'A+',
                'last_visit' => '2026-02-15',
                'condition' => 'التهاب المفاصل',
                'avatar' => 'https://ui-avatars.com/api/?name=Mahmoud+Hasan&background=0D9488&color=fff'
            ],
            [
                'id' => 4,
                'name' => 'ليلى يوسف',
                'age' => 50,
                'gender' => 'أنثى',
                'blood_type' => 'B+',
                'last_visit' => '2026-02-27',
                'condition' => 'أمراض القلب',
                'avatar' => 'https://ui-avatars.com/api/?name=Layla+Youssef&background=0D9488&color=fff'
            ],
        ];

        return view('doctor.patients.index', compact('patients', 'stats'));
    }

    public function show($id)
    {
        // Detailed mock data for a single patient profile
        $patient = [
            'id' => $id,
            'name' => 'أحمد محمد علي',
            'age' => 45,
            'gender' => 'ذكر',
            'blood_type' => 'O+',
            'height' => '178 cm',
            'weight' => '82 kg',
            'avatar' => 'https://ui-avatars.com/api/?name=Ahmed+Mohamed&background=0D9488&color=fff',
            'vitals_history' => [
                ['date' => '2026-02-20', 'bp' => '135/85', 'pulse' => '72'],
                ['date' => '2026-02-10', 'bp' => '140/90', 'pulse' => '78'],
                ['date' => '2026-01-25', 'bp' => '138/88', 'pulse' => '75'],
            ],
            'medications' => [
                ['name' => 'Amlodipine', 'dosage' => '5mg', 'status' => 'Active'],
                ['name' => 'Metformin', 'dosage' => '500mg', 'status' => 'Paused'],
                ['name' => 'Aspirin', 'dosage' => '75mg', 'status' => 'Active'],
            ],
            'timeline' => [
                [
                    'date' => '2026-02-20',
                    'type' => 'زيارة عيادة',
                    'desc' => 'فحص دوري لمتابعة ضغط الدم. تم تعديل الجرعة الدوائية.',
                    'doctor' => 'د. جون دو',
                    'icon' => 'fas fa-stethoscope',
                    'color' => 'teal'
                ],
                [
                    'date' => '2026-01-15',
                    'type' => 'تقرير مختبر',
                    'desc' => 'نتائج تحليل الكوليسترول والدهون الثلاثية. ارتفاع طفيف.',
                    'doctor' => 'مختبر الأمل',
                    'icon' => 'fas fa-flask',
                    'color' => 'sky'
                ],
                [
                    'date' => '2025-11-10',
                    'type' => 'وصفة طبية',
                    'desc' => 'صرف علاج جديد لآلام المفاصل البسيطة.',
                    'doctor' => 'د. جون دو',
                    'icon' => 'fas fa-pills',
                    'color' => 'orange'
                ],
            ]
        ];

        return view('doctor.patients.show', compact('patient'));
    }
}
