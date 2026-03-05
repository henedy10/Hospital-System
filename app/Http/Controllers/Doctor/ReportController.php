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
            ['label' => 'إجمالي التقارير', 'value' => 128, 'change' => '+12%', 'icon' => 'fas fa-file-medical', 'color' => 'bg-teal'],
            ['label' => 'قيد المراجعة', 'value' => 14, 'change' => '-5%', 'icon' => 'fas fa-clock', 'color' => 'bg-amber'],
            ['label' => 'دقة التشخيص', 'value' => '94%', 'change' => '+2%', 'icon' => 'fas fa-chart-line', 'color' => 'bg-rose'],
        ];

        $reports = [
            ['id' => 'R-1024', 'name' => 'تقرير فحص دوري - أحمد محمد', 'date' => '2026-02-28', 'category' => 'فحص عام', 'status' => 'جاهز', 'status_type' => 'success'],
            ['id' => 'R-1025', 'name' => 'تحليل دم شامل - سارة علي', 'date' => '2026-02-27', 'category' => 'مختبر', 'status' => 'قيد المعالجة', 'status_type' => 'warning'],
            ['id' => 'R-1026', 'name' => 'أشعة رنين مغناطيسي - محمود حسن', 'date' => '2026-02-26', 'category' => 'أشعة', 'status' => 'مراجعة', 'status_type' => 'info'],
            ['id' => 'R-1027', 'name' => 'تقرير جراحة قلب - ليلي يوسف', 'date' => '2026-02-25', 'category' => 'جراحة', 'status' => 'جاهز', 'status_type' => 'success'],
            ['id' => 'R-1028', 'name' => 'فحص عظام - عمر خالد', 'date' => '2026-02-24', 'category' => 'عظام', 'status' => 'ملغي', 'status_type' => 'danger'],
        ];

        return view('doctor.reports.index', compact('stats', 'reports'));
    }

    public function show($id)
    {
        // Detailed mock data for a "Best Case" report
        $report = [
            'id' => $id,
            'name' => 'تقرير فحص دوري شامل',
            'date' => '2026-02-28',
            'category' => 'Cardiology',
            'department_ar' => 'قسم القلب والأوعية الدموية',
            'status' => 'جاهز للمراجعة النهائية',
            'patient' => [
                'name' => 'أحمد محمد علي',
                'id' => 'P-99201',
                'age' => 45,
                'weight' => '82 kg',
                'blood_type' => 'O+',
                'avatar' => 'https://ui-avatars.com/api/?name=Ahmed+Mohamed&background=0D9488&color=fff'
            ],
            'diagnosis' => 'اشتباه في ارتفاع بسيط في ضغط الدم التوتري مع انتظام في ضربات القلب. يُنصح بالمتابعة الدورية وتقليل نسبة الصوديوم في الغذاء.',
            'vitals' => [
                ['label' => 'الضغط', 'value' => '135/85', 'status' => 'High Normal'],
                ['label' => 'نبض القلب', 'value' => '72 bpm', 'status' => 'Regular'],
                ['label' => 'درجة الحرارة', 'value' => '37.1 °C', 'status' => 'Normal'],
            ],
            'clinical_notes' => 'المريض يعاني من إجهاد عمل مستمر وصداع نصفي متكرر. أجريت فحوصات الأسبوع الماضي وكانت النتائج مقبولة عدا ارتفاع طفيف في الكوليسترول.',
            'treatment_plan' => [
                'قرص واحد املوديبين (5mg) مساءً.',
                'ممارسة رياضة المشي 30 دقيقة يومياً.',
                'إعادة التحليل بعد أسبوعين.'
            ]
        ];

        return view('doctor.reports.show', compact('report'));
    }
}
