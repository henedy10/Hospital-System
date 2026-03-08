<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = auth()->user();

        // 1. Daily Patients (Appointments for today)
        $dailyPatients = $doctor->doctorAppointments()->whereDate('appointment_date', today())->count();

        // 2. Total Unique Patients
        $totalPatients = $doctor->doctorAppointments()->distinct('user_id')->count('user_id');

        // 3. Upcoming Appointments
        $upcomingAppointments = $doctor->doctorAppointments()->where('status', 'upcoming')->count();

        // 4. Monthly patient data for the chart (Current Year)
        $appointmentsThisYear = $doctor->doctorAppointments()
            ->whereYear('appointment_date', date('Y'))
            ->get(['appointment_date']);

        $monthlyDataArray = array_fill(1, 12, 0);
        foreach ($appointmentsThisYear as $app) {
            $month = (int) \Carbon\Carbon::parse($app->appointment_date)->format('m');
            $monthlyDataArray[$month]++;
        }

        $monthlyData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => array_values($monthlyDataArray)
        ];

        return view('doctor.index', compact('dailyPatients', 'totalPatients', 'upcomingAppointments', 'monthlyData'));
    }
}
