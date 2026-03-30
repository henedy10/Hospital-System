<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $todayTasks = $user->tasks()
            ->whereDate('due_at', now()->today())
            ->orderBy('due_at', 'asc')
            ->get();

        $upcomingTasks = $user->tasks()
            ->whereDate('due_at', '>', now()->today())
            ->orderBy('due_at', 'asc')
            ->get();

        $tasks = [
            'today' => $todayTasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'time' => $task->due_at->format('h:i AM'),
                    'due_at' => $task->due_at,
                    'status' => $task->status,
                    'category' => $task->category,
                    'priority' => $task->priority,
                    'patient_id' => $task->patient_id,
                ];
            })->toArray(),
            'upcoming' => $upcomingTasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'time' => $task->due_at->format('h:i AM'),
                    'due_at' => $task->due_at,
                    'status' => $task->status,
                    'category' => $task->category,
                    'priority' => $task->priority,
                    'patient_id' => $task->patient_id,
                ];
            })->toArray()
        ];

        return view('nurse.tasks', compact('tasks'));
    }
}
