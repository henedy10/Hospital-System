<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = [
            'recent_appointments' => $user->appointments()
                ->orderBy('appointment_date', 'asc')
                ->orderBy('appointment_time', 'asc')
                ->take(5)
                ->get(),
            'medical_history' => $user->medicalHistories()
                ->orderBy('diagnosis_date', 'desc')
                ->take(5)
                ->get(),
            'latest_vitals' => $user->vitals()
                ->latest()
                ->first(),
        ];

        return view('patient.dashboard', $data);
    }
}
