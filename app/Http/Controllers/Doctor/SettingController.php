<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Mock user settings data
        $user = [
            'name' => 'د. جون دو',
            'email' => 'dr.john@hospital.com',
            'phone' => '+20 123 456 789',
            'specialization' => 'استشاري جراحة القلب والأوعية الدموية',
            'bio' => 'خبرة أكثر من 15 عاماً في جراحة القلب المفتوح والقسطرة التداخلية.',
            'avatar' => 'https://ui-avatars.com/api/?name=John+Doe&background=0D9488&color=fff',
            'notifications' => [
                'email' => true,
                'sms' => false,
                'appointments' => true,
                'reports' => true,
            ],
            'security' => [
                'two_factor' => false,
            ]
        ];

        return view('doctor.settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        // In a real app, this would handle updating settings
        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
