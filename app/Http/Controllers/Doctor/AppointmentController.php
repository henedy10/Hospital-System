<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        // Mock data for appointments
        $appointments = [
            [
                'id' => 1,
                'patient_name' => 'أحمد محمد',
                'time' => '10:30 AM',
                'date' => '2026-02-28',
                'type' => 'كشف جديد',
                'status' => 'Confirmed',
                'status_ar' => 'تم التأكيد'
            ],
            [
                'id' => 2,
                'patient_name' => 'سارة علي',
                'time' => '11:15 AM',
                'date' => '2026-02-28',
                'type' => 'استشارة',
                'status' => 'Pending',
                'status_ar' => 'قيد الانتظار'
            ],
            [
                'id' => 3,
                'patient_name' => 'محمود حسن',
                'time' => '12:00 PM',
                'date' => '2026-02-28',
                'type' => 'متابعة',
                'status' => 'Cancelled',
                'status_ar' => 'ملغي'
            ],
            [
                'id' => 4,
                'patient_name' => 'ليلى يوسف',
                'time' => '01:30 PM',
                'date' => '2026-02-28',
                'type' => 'كشف جديد',
                'status' => 'Confirmed',
                'status_ar' => 'تم التأكيد'
            ],
            [
                'id' => 5,
                'patient_name' => 'عمر خالد',
                'time' => '02:15 PM',
                'date' => '2026-02-28',
                'type' => 'متابعة',
                'status' => 'Confirmed',
                'status_ar' => 'تم التأكيد'
            ],
        ];

        return view('doctor.appointments.index', compact('appointments'));
    }
}
