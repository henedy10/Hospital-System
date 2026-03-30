<?php

namespace App\Http\Controllers\Nurse;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Patient;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Real data for the nurse dashboard
        $assignedPatients = $user->assignedPatients()->count();
        $urgentTasks = $user->tasks()->where('status', 'pending')->where('priority', 'High')->count();
        $medicationDue = $user->tasks()
            ->where('status', 'pending')
            ->where('category', 'Clinical')
            ->where('title', 'LIKE', '%Medication%')
            ->whereDate('due_at', now()->today())
            ->count();

        // Activity distribution data for the chart based on task counts by category
        $activities = $user->tasks()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $activityData = [
            'labels' => array_keys($activities),
            'data' => array_values($activities)
        ];

        // Ensure we have some default labels if no tasks exist yet
        if (empty($activityData['labels'])) {
            $activityData = [
                'labels' => ['Medication', 'Vitals', 'Wound Care', 'Hygiene', 'Assessment'],
                'data' => [0, 0, 0, 0, 0]
            ];
        }

        return view('nurse.dashboard', compact('assignedPatients', 'urgentTasks', 'medicationDue', 'activityData'));
    }
}
