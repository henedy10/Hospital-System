<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $doctor */
        $doctor = Auth::user();
        $today = Carbon::today();

        // 1. Today's appointments count
        $dailyPatients = $doctor->doctorAppointments()->whereDate('appointment_date', $today)->count();

        // 2. Total unique patients (ever had an appointment with this doctor)
        $totalPatients = $doctor->doctorAppointments()->distinct('user_id')->count('user_id');

        // 3. Upcoming: future appointments with status 'upcoming'
        $upcomingCount = $doctor->doctorAppointments()
            ->where('status', 'upcoming')
            ->whereDate('appointment_date', '>=', $today)
            ->count();

        // 4. Completed today
        $completedToday = $doctor->doctorAppointments()
            ->whereDate('appointment_date', $today)
            ->where('status', 'completed')
            ->count();

        // 5. Today's schedule (for dashboard list)
        $todayAppointments = $doctor->doctorAppointments()
            ->whereDate('appointment_date', $today)
            ->with('user:id,name,profile_image')
            ->orderBy('appointment_time')
            ->get();

        // 6. Next upcoming appointments (next 5)
        $upcomingAppointments = $doctor->doctorAppointments()
            ->where('status', 'upcoming')
            ->whereDate('appointment_date', '>=', $today)
            ->with('user:id,name,profile_image')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        // 7. Monthly chart data (current year)
        $currentYear = (int) date('Y');
        $appointmentsThisYear = $doctor->doctorAppointments()
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
