<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSymptomCheckRequest;
use App\Services\AISymptomCheckerService;
use App\Models\SymptomCheck;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class SymptomCheckController extends Controller
{
    protected $aiService;

    public function __construct(AISymptomCheckerService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        return view('symptoms.index');
    }

    public function analyze(StoreSymptomCheckRequest $request)
    {
        $user = Auth::user();
        if (!$user->patient) {
            return redirect()->back()->with('error', 'Only registered patients can use the symptom checker.');
        }

        $symptomsData = $request->validated();
        
        // Ensure all keys are present even if not checked (the Request takes care of this in prepareForValidation)
        $aiResponse = $this->aiService->analyzeSymptoms($symptomsData);

        $symptomCheck = SymptomCheck::create([
            'patient_id' => $user->patient->id,
            'symptoms_json' => $symptomsData,
            'predicted_disease' => $aiResponse['predicted_disease'] ?? 'Unknown',
            'specialization' => $aiResponse['specialization'] ?? 'General Medicine',
            'urgency' => $aiResponse['urgency'] ?? 'medium',
        ]);

        return redirect()->route('symptoms.result', $symptomCheck->id);
    }

    public function result($id)
    {
        $symptomCheck = SymptomCheck::with('patient')->findOrFail($id);

        if ($symptomCheck->patient->user_id !== Auth::id()) {
            abort(403);
        }

        $specialization = $symptomCheck->specialization;
        
        $recommendedDoctors = collect();
        if ($specialization && $symptomCheck->urgency !== 'high') { 
            $recommendedDoctors = Doctor::with('user')
                ->where('specialty', 'LIKE', '%' . $specialization . '%')
                ->take(3)
                ->get();
        }

        return view('symptoms.result', compact('symptomCheck', 'recommendedDoctors'));
    }

    public function history()
    {
        $user = Auth::user();
        if (!$user->patient) {
            return redirect()->back()->with('error', 'Only registered patients have symptom history.');
        }

        $history = SymptomCheck::where('patient_id', $user->patient->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('symptoms.history', compact('history'));
    }
}
