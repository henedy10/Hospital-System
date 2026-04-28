<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Services\PrescriptionExplainerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function __construct(
        protected PrescriptionExplainerService $explainerService
    ) {}

    /**
     * List all prescriptions for the logged-in patient.
     */
    public function index()
    {
        $patient = Auth::user()->patient;

        $prescriptions = Prescription::where('patient_id', $patient->id)
            ->with(['doctor.user', 'items'])
            ->latest()
            ->paginate(10);

        return view('patient.prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show a single prescription (structured view).
     */
    public function show(Prescription $prescription)
    {
        $patient = Auth::user()->patient;

        abort_if($prescription->patient_id !== $patient->id, 403);

        $prescription->load(['doctor.user', 'items']);

        return view('patient.prescriptions.show', compact('prescription'));
    }

    /**
     * GET /patient/prescriptions/{id}/explain
     * Generate and display the Arabic AI explanation.
     */
    public function explain(Prescription $prescription)
    {
        $patient = Auth::user()->patient;

        abort_if($prescription->patient_id !== $patient->id, 403);

        $prescription->load(['doctor.user', 'patient.user', 'items']);

        // Generate explanation using the service
        $explanation = $this->explainerService->explain($prescription);

        // Also support JSON response for API consumers
        if (request()->expectsJson()) {
            return response()->json($explanation);
        }

        return view('patient.prescriptions.explain', compact('prescription', 'explanation'));
    }
}
