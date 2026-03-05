<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Mock data for the dashboard
        $dailyPatients = 24;
        $emergencyCases = 3;

        // Monthly patient data for the chart
        $monthlyData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => [65, 59, 80, 81, 56, 55, 40, 45, 60, 75, 90, 100]
        ];

        return view('doctor.index', compact('dailyPatients', 'emergencyCases', 'monthlyData'));
    }
}
