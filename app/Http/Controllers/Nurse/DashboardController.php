<?php

namespace App\Http\Controllers\Nurse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Mock data for the nurse dashboard
        $assignedPatients = 12;
        $urgentTasks = 4;
        $medicationDue = 8;

        // Activity distribution data for the chart
        $activityData = [
            'labels' => ['Medication', 'Vitals', 'Wound Care', 'Hygiene', 'Assessment'],
            'data' => [45, 30, 10, 5, 10]
        ];

        return view('nurse.dashboard', compact('assignedPatients', 'urgentTasks', 'medicationDue', 'activityData'));
    }
}
