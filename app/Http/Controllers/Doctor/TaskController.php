<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreTaskRequest;
use App\Models\Patient;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class TaskController extends Controller
{
    /**
     * List all tasks assigned by this doctor.
     */
    public function index(Request $request)
    {
        $query = Task::where('assigned_by', Auth::id())
            ->with(['nurse', 'patient.user', 'assignedBy']);

        // Filtering
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->orderByRaw("CASE priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 ELSE 3 END")
                       ->orderBy('due_at', 'asc')
                       ->paginate(15)
                       ->withQueryString();

        $stats = [
            'total'   => Task::where('assigned_by', Auth::id())->count(),
            'pending' => Task::where('assigned_by', Auth::id())->where('status', 'pending')->count(),
            'high'    => Task::where('assigned_by', Auth::id())->where('priority', 'High')->where('status', 'pending')->count(),
            'done'    => Task::where('assigned_by', Auth::id())->where('status', 'completed')->count(),
        ];

        return view('doctor.tasks.index', compact('tasks', 'stats'));
    }

    /**
     * Show the form to assign a new task to a nurse.
     */
    public function create()
    {
        $nurses   = User::where('role', 'nurse')->orderBy('name')->get();
        $patients = Patient::with('user')->orderBy('created_at', 'desc')->get();

        return view('doctor.tasks.create', compact('nurses', 'patients'));
    }

    /**
     * Store the new task and notify the nurse.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $task = Task::create([
            'user_id'     => $request->nurse_id,
            'assigned_by' => Auth::id(),
            'patient_id'  => $request->patient_id ?: null,
            'title'       => $request->title,
            'description' => $request->description,
            'notes'       => $request->notes,
            'category'    => $request->category,
            'priority'    => $request->priority,
            'due_at'      => $request->due_at,
            'status'      => 'pending',
        ]);

        // Notify the assigned nurse
        $nurse = User::find($request->nurse_id);
        $patient = Patient::with('user')->find($request->patient_id);
        if ($nurse) {
            $nurse->notify(new TaskAssignedNotification($task));
        }

        Http::post('https://finicky-unstuffed-rewrap.ngrok-free.dev/webhook-test/4602b80f-14af-4374-b2b0-d3fe402f2104',
        [
            'title' => 'New Task Assigned',
            'priority' => $task->priority,
            'nurse_email' => $nurse->email,
            'deadline' => $task->due_at,
            'doctor_name' => Auth::user()->name,
            'patient_name' => $patient->user->name
        ]);
    } catch (\Exception $e) {
        log::error($e->getMessage());
        return redirect()->route('doctor.tasks.index')
            ->with('error', "Failed to assign task: " . $e->getMessage());
    }

        return redirect()->route('doctor.tasks.index')
            ->with('success', "Task \"{$task->title}\" assigned to {$nurse->name} successfully.");
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task)
    {
        // Only the doctor who created it can delete it
        if ($task->assigned_by !== Auth::id()) {
            abort(403);
        }

        $title = $task->title;
        $task->delete();

        return redirect()->route('doctor.tasks.index')
            ->with('success', "Task \"{$title}\" deleted.");
    }
}
