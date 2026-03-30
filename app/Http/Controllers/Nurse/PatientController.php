<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Task;

class PatientController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Real patients under nurse care
        $patients_query = $user->assignedPatients()->with(['user', 'vitals']);
        $patients_data = $patients_query->get();

        // Stats for the nurse patients page
        $stats = [
            ['label' => 'Total Patients', 'value' => $patients_data->count(), 'icon' => 'fas fa-users', 'color' => 'bg-teal'],
            ['label' => 'Critical Cases', 'value' => $patients_data->where('status', 'Critical')->count(), 'icon' => 'fas fa-exclamation-triangle', 'color' => 'bg-red'],
            ['label' => 'Pending Vitals', 'value' => $user->tasks()->where('status', 'pending')->where('title', 'LIKE', '%Vitals%')->count(), 'icon' => 'fas fa-heartbeat', 'color' => 'bg-amber'],
        ];

        $patients = $patients_data->map(function ($patient) {
            return [
                'id' => $patient->id,
                'room' => $patient->room ?? 'N/A', // Assuming room might be added later or using N/A
                'name' => $patient->user->name,
                'age' => $patient->dob ? $patient->dob->age : 'N/A',
                'gender' => $patient->gender,
                'condition' => 'Recovering', // Simplified for now
                'last_vitals' => $patient->vitals->last() ? $patient->vitals->last()->created_at->format('h:i A') : 'N/A',
                'next_dose' => 'TBD',
                'status' => 'Stable', // Default status
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($patient->user->name) . '&background=0D9488&color=fff'
            ];
        });

        return view('nurse.patients.index', compact('patients', 'stats'));
    }

    public function show($id)
    {
        $patient = Patient::with(['user', 'vitals'])->findOrFail($id);

        $vitalsHistory = $patient->vitals->sortByDesc('created_at')->map(function ($v) {
            return [
                'time' => $v->created_at->format('h:i A'),
                'bp' => $v->blood_pressure,
                'temp' => $v->temperature,
                'pulse' => $v->heart_rate,
                'oxygen' => $v->spo2 . '%',
            ];
        });

        // Fetch medication tasks for this patient
        $medTasks = Task::where('patient_id', $id)
            ->where('category', 'Clinical')
            ->where('title', 'LIKE', '%Medication%')
            ->orderBy('due_at', 'asc')
            ->get()
            ->map(function ($t) {
                return [
                    'time' => $t->due_at->format('h:i A'),
                    'med' => $t->title,
                    'status' => ucfirst($t->status),
                    'nurse' => $t->nurse->name ?? 'System',
                ];
            });

        $patientData = [
            'id' => $patient->id,
            'room' => $patient->room ?? 'N/A',
            'name' => $patient->user->name,
            'age' => $patient->dob ? $patient->dob->age : 'N/A',
            'birth_date' => $patient->dob ? $patient->dob->format('Y-m-d') : 'N/A',
            'gender' => $patient->gender,
            'blood_type' => $patient->blood_type ?? 'N/A',
            'allergies' => $patient->allergies ?? [],
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($patient->user->name) . '&background=0D9488&color=fff',
            'vitals_history' => $vitalsHistory,
            'medication_schedule' => $medTasks,
        ];

        return view('nurse.patients.show', ['patient' => $patientData]);
    }
}
