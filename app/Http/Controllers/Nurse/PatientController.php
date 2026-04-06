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
        $nurseSpecialty = optional($user->nurse)->speciality;

        // Fetch patients related to doctors with the same specialty as the nurse
        $patients_query = Patient::query()
            ->where(function ($query) use ($user, $nurseSpecialty) {
                // Patients directly assigned to this nurse
                $query->where('nurse_id', $user->id);
                
                // OR Patients who have appointments with doctors of the same specialty
                if ($nurseSpecialty) {
                    $query->orWhereHas('appointments.doctor', function ($q) use ($nurseSpecialty) {
                        $q->where('specialty', $nurseSpecialty);
                    });
                }
            })
            ->with(['user', 'vitals'])
            ->distinct();

        $patients_data = $patients_query->get();

        // Stats for the nurse patients page
        $stats = [
            ['label' => 'Total Patients', 'value' => $patients_data->count(), 'icon' => 'fas fa-users', 'color' => 'bg-teal'],
            ['label' => 'Critical Cases', 'value' => $patients_data->where('status', 'Critical')->count(), 'icon' => 'fas fa-exclamation-triangle', 'color' => 'bg-red'],
            ['label' => 'Pending Vitals', 'value' => $user->tasks()->where('status', 'pending')->where('title', 'LIKE', '%Vitals%')->count(), 'icon' => 'fas fa-heartbeat', 'color' => 'bg-amber'],
        ];

        $patients = $patients_data->map(function ($patient) {
            $lastVital = $patient->vitals->last();
            return [
                'id' => $patient->id,
                'room' => $patient->room ?? 'Room 10' . ($patient->id % 9),
                'name' => $patient->user->name,
                'age' => $patient->dob ? (is_string($patient->dob) ? \Carbon\Carbon::parse($patient->dob)->age : $patient->dob->age) : 'N/A',
                'gender' => $patient->gender,
                'condition' => 'Recovering',
                'last_vitals_time' => $lastVital ? $lastVital->created_at->format('h:i A') : 'N/A',
                'last_bp' => $lastVital->blood_pressure ?? 'N/A',
                'last_hr' => $lastVital->heart_rate ?? 'N/A',
                'last_temp' => $lastVital->temperature ?? 'N/A',
                'next_dose' => 'TBD',
                'status' => $patient->status ?? 'Stable',
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
                'recorder' => optional(optional($v->nurse)->user)->name ?? 'System',
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
            'room' => $patient->room ?? 'Room 10' . ($patient->id % 9),
            'name' => $patient->user->name,
            'age' => $patient->dob ? (is_string($patient->dob) ? \Carbon\Carbon::parse($patient->dob)->age : $patient->dob->age) : 'N/A',
            'birth_date' => $patient->dob instanceof \Carbon\Carbon ? $patient->dob->format('Y-m-d') : ($patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('Y-m-d') : 'N/A'),
            'gender' => $patient->gender,
            'blood_type' => $patient->blood_type ?? 'N/A',
            'allergies' => $patient->allergies ?? [],
            'status' => $patient->status ?? 'Stable',
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($patient->user->name) . '&background=0D9488&color=fff',
            'vitals_history' => $vitalsHistory,
            'medication_schedule' => $medTasks,
        ];

        return view('nurse.patients.show', ['patient' => $patientData]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Stable,Critical,Under Observation,Recovering,Discharged',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Patient status updated to ' . $request->status);
    }
}
