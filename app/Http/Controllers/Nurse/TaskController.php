<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user     = Auth::user();
        $priority = $request->filled('priority') && in_array($request->priority, ['Low', 'Medium', 'High'])
                    ? $request->priority : null;
        $status   = $request->filled('status') && in_array($request->status, ['pending', 'completed', 'overdue'])
                    ? $request->status : null;
        $search   = $request->filled('search') ? $request->search : null;

        /**
         * Build a fresh filtered query each time so filters never bleed
         * across different date scopes (today / upcoming / overdue).
         */
        $buildQuery = function () use ($user, $priority, $status, $search) {
            $q = $user->tasks()->with(['assignedBy', 'patient.user']);
            if ($priority) $q->where('priority', $priority);
            if ($status)   $q->where('status', $status);
            if ($search)   $q->where('title', 'like', "%{$search}%");
            return $q;
        };

        // Today's tasks — sorted High → Medium → Low then by time
        $todayTasks = $buildQuery()
            ->whereDate('due_at', now()->toDateString())
            ->orderByRaw("CASE priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 ELSE 3 END")
            ->orderBy('due_at', 'asc')
            ->get();

        // Upcoming tasks (future dates)
        $upcomingTasks = $buildQuery()
            ->whereDate('due_at', '>', now()->toDateString())
            ->orderByRaw("CASE priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 ELSE 3 END")
            ->orderBy('due_at', 'asc')
            ->get();

        // Overdue = pending tasks with a past due date (never filters via $status chip)
        $overdueTasks = $user->tasks()
            ->with(['assignedBy', 'patient.user'])
            ->where('status', 'pending')
            ->whereDate('due_at', '<', now()->toDateString())
            ->when($priority, fn($q) => $q->where('priority', $priority))
            ->when($search,   fn($q) => $q->where('title', 'like', "%{$search}%"))
            ->orderByRaw("CASE priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 ELSE 3 END")
            ->orderBy('due_at', 'asc')
            ->get();

        // Stats — always reflect total (not filtered)
        $stats = [
            'pending'   => $user->tasks()->where('status', 'pending')->count(),
            'completed' => $user->tasks()->where('status', 'completed')->count(),
            'high'      => $user->tasks()->where('priority', 'High')->where('status', 'pending')->count(),
            'overdue'   => $user->tasks()->where('status', 'pending')
                               ->whereDate('due_at', '<', now()->toDateString())->count(),
        ];

        return view('nurse.tasks', compact('todayTasks', 'upcomingTasks', 'overdueTasks', 'stats'));
    }

    /**
     * Toggle task status: pending ↔ completed
     */
    public function updateStatus(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $newStatus = ($task->status === 'completed') ? 'pending' : 'completed';
        $task->update(['status' => $newStatus]);

        return response()->json([
            'status'  => $newStatus,
            'message' => $newStatus === 'completed' ? 'Task marked as complete!' : 'Task marked as pending.',
        ]);
    }
}
