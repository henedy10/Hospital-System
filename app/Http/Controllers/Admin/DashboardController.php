<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDoctors = User::where('role', User::ROLE_DOCTOR)->count();
        $totalNurses = User::where('role', User::ROLE_NURSE)->count();
        $totalPatients = User::where('role', User::ROLE_PATIENT)->count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'upcoming')->count();

        // Monthly appointments data for chart
        $appointmentsThisYear = Appointment::whereYear('appointment_date', date('Y'))
            ->get(['appointment_date']);

        $monthlyDataArray = array_fill(1, 12, 0);
        foreach ($appointmentsThisYear as $app) {
            $month = (int) \Carbon\Carbon::parse($app->appointment_date)->format('m');
            $monthlyDataArray[$month]++;
        }

        $monthlyData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => array_values($monthlyDataArray),
        ];

        // Recent users (last 8)
        $recentUsers = User::orderByDesc('created_at')->take(8)->get();

        return view('admin.index', compact(
            'totalDoctors',
            'totalNurses',
            'totalPatients',
            'todayAppointments',
            'totalAppointments',
            'pendingAppointments',
            'monthlyData',
            'recentUsers'
        ));
    }
}
