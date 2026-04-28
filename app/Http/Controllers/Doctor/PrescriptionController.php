<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    /**
     * List all prescriptions written by this doctor.
     */
    public function index()
    {
        $doctor = Auth::user()->doctor;

        $prescriptions = Prescription::where('doctor_id', $doctor->id)
            ->with(['patient.user', 'items'])
            ->latest()
            ->paginate(10);

        return view('doctor.prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show the form to create a new prescription.
     */
    public function create(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        $report_id = $request->query('report_id');
        $medical_history = null;
        if ($report_id) {
            $medical_history = \App\Models\MedicalHistory::where('id', $report_id)->where('doctor_id', $doctor->id)->first();
        }

        // Fetch patients assigned to this doctor (via appointments)
        $patients = Patient::whereHas('appointments', function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        })->with('user')->get();

        return view('doctor.prescriptions.create', compact('patients', 'medical_history'));
    }

    /**
     * Store a newly created prescription.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'              => ['required', 'exists:patients,id'],
            'medical_history_id'      => ['nullable', 'exists:medical_histories,id'],
            'notes'                   => ['nullable', 'string', 'max:1000'],
            'items'                   => ['required', 'array', 'min:1'],
            'items.*.medicine_name'   => ['required', 'string', 'max:255'],
            'items.*.dosage'          => ['required', 'string', 'max:100'],
            'items.*.frequency'       => ['required', 'integer', 'min:1', 'max:10'],
            'items.*.duration'        => ['required', 'integer', 'min:1', 'max:365'],
            'items.*.instructions'    => ['nullable', 'string', 'max:500'],
        ]);

        $doctor = Auth::user()->doctor;

        DB::transaction(function () use ($validated, $doctor) {
            $prescription = Prescription::create([
                'patient_id'         => $validated['patient_id'],
                'doctor_id'          => $doctor->id,
                'medical_history_id' => $validated['medical_history_id'] ?? null,
                'notes'              => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $itemData) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_name'   => $itemData['medicine_name'],
                    'dosage'          => $itemData['dosage'],
                    'frequency'       => $itemData['frequency'],
                    'duration'        => $itemData['duration'],
                    'instructions'    => $itemData['instructions'] ?? null,
                ]);
            }
        });

        return redirect()->route('doctor.prescriptions.index')
            ->with('success', 'تم إنشاء الوصفة الطبية بنجاح.');
    }

    /**
     * Display a prescription detail.
     */
    public function show(Prescription $prescription)
    {
        $doctor = Auth::user()->doctor;

        abort_if($prescription->doctor_id !== $doctor->id, 403);

        $prescription->load(['patient.user', 'items']);

        return view('doctor.prescriptions.show', compact('prescription'));
    }

    /**
     * Delete a prescription.
     */
    public function destroy(Prescription $prescription)
    {
        $doctor = Auth::user()->doctor;

        abort_if($prescription->doctor_id !== $doctor->id, 403);

        $prescription->delete();

        return redirect()->route('doctor.prescriptions.index')
            ->with('success', 'تم حذف الوصفة الطبية بنجاح.');
    }
}
