<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $doctor */
        $doctor = Doctor::with('user')->whereHas('user', function ($q){
            $q->where('id',Auth::id());
        })->first();
        $today = Carbon::today();

        // 1. Today's appointments count
        $dailyPatients = Appointment::where('doctor_id',$doctor->id)->where('status','!=','cancelled')->whereDate('appointment_date', $today)->count();

        // 2. Total unique patients (ever had an appointment with this doctor)
        $totalPatients = Appointment::where('doctor_id',$doctor->id)->distinct('patient_id')->count('patient_id');

        // 3. Upcoming: future appointments with status 'upcoming'
        $upcomingCount = Appointment::where('doctor_id',$doctor->id)
            ->where('status', 'upcoming')
            ->whereDate('appointment_date', '>=', $today)
            ->count();

        // 4. Completed today
        $completedToday = Appointment::where('doctor_id',$doctor->id)
            ->whereDate('appointment_date', $today)
            ->where('status', 'completed')
            ->count();

        // 5. Today's schedule (for dashboard list)
        $todayAppointments = Appointment::where('doctor_id',$doctor->id)
            ->whereDate('appointment_date', $today)
            ->where('status','!=','cancelled')
            ->with('patient.user:id,name,profile_image')
            ->orderBy('appointment_time')
            ->get();

        // 6. Next upcoming appointments (next 5)
        $upcomingAppointments = Appointment::where('doctor_id',$doctor->id)
            ->where('status', 'upcoming')
            ->whereDate('appointment_date', '>=', $today)
            ->with('patient.user:id,name,profile_image')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        // 7. Monthly chart data (current year)
        $currentYear = (int) date('Y');
        $appointmentsThisYear = Appointment::where('doctor_id',$doctor->id)
            ->whereYear('appointment_date', $currentYear)
            ->get(['appointment_date']);

        $monthlyDataArray = array_fill(1, 12, 0);
        foreach ($appointmentsThisYear as $app) {
            $month = (int) Carbon::parse($app->appointment_date)->format('n');
            $monthlyDataArray[$month]++;
        }

        $monthlyData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => array_values($monthlyDataArray),
            'year' => $currentYear,
        ];

        return view('doctor.index', [
            'doctor' => $doctor,
            'dailyPatients' => $dailyPatients,
            'totalPatients' => $totalPatients,
            'upcomingCount' => $upcomingCount,
            'completedToday' => $completedToday,
            'todayAppointments' => $todayAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            'monthlyData' => $monthlyData,
        ]);
    }
}
