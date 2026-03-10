<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Upcoming: future appointments (date >= today), not cancelled, ordered by date/time
        $upcomingAppointments = $user->appointments()
            ->where('appointment_date', '>=', $today)
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        $nextAppointment = $upcomingAppointments->first();

        // Counts for stats
        $upcomingCount = $user->appointments()
            ->where('appointment_date', '>=', $today)
            ->where('status', 'upcoming')
            ->count();

        $medicalRecordsCount = $user->medicalHistories()->count();

        $latestVitals = $user->vitals()->latest()->first();

        // Recent medical history (for the list)
        $recentMedicalHistory = $user->medicalHistories()
            ->orderByDesc('diagnosis_date')
            ->limit(5)
            ->get();

        return view('patient.dashboard', [
            'user' => $user,
            'nextAppointment' => $nextAppointment,
            'upcomingAppointments' => $upcomingAppointments,
            'upcomingCount' => $upcomingCount,
            'medicalRecordsCount' => $medicalRecordsCount,
            'latestVitals' => $latestVitals,
            'recentMedicalHistory' => $recentMedicalHistory,
        ]);
    }
}
