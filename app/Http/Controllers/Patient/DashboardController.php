<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\
{
    Appointment,
    MedicalHistory,
    User
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::with('patient')->where('id',Auth::id())->first();
        $today = Carbon::today();
        // Upcoming: future appointments (date >= today), not cancelled, ordered by date/time
        $upcomingAppointments = Appointment::with('user')
            ->where('patient_id',$user->patient->id)
            ->where('appointment_date', '>=', $today)
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        $nextAppointment = $upcomingAppointments->first();

        // Counts for stats
        $upcomingCount = Appointment::where('appointment_date', '>=', $today)
            ->where('status', 'upcoming')
            ->count();


            $latestVitals = $user->vitals()->latest()->first();

            // Recent medical history (for the list)
        $medicalRecordsCount  = MedicalHistory::where('patient_id',$user->patient->id)->count() ;
        $recentMedicalHistory = MedicalHistory::with(['prescription', 'doctor.user'])
                                            ->where('patient_id',$user->patient->id)
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
