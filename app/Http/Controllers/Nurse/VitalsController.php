<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vital;
use App\Models\Patient;

class VitalsController extends Controller
{
    public function create($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        return view('nurse.vitals.create', compact('patientId', 'patient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'temperature' => 'nullable|numeric',
            'blood_pressure' => 'nullable|string',
            'heart_rate' => 'nullable|numeric',
            'respiratory_rate' => 'nullable|numeric',
            'spo2' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        Vital::create([
            'user_id' => $patient->user_id, // Vital belongs to the patient's user record
            'temperature' => $request->temperature,
            'blood_pressure' => $request->blood_pressure,
            'heart_rate' => $request->heart_rate,
            'respiratory_rate' => $request->respiratory_rate,
            'spo2' => $request->spo2,
            'notes' => $request->notes,
            'recorded_by' => optional(auth()->user()->nurse)->id,
        ]);

        return redirect()->route('nurse.patients.show', $request->patient_id)
            ->with('success', 'Vitals recorded successfully.');
    }
}
