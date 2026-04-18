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

        $symptoms = $request->validated()['symptoms'];
        $aiResponse = $this->aiService->analyzeSymptoms($symptoms);

        $symptomCheck = SymptomCheck::create([
            'patient_id' => $user->patient->id,
            'symptoms_text' => $symptoms,
            'ai_response' => $aiResponse,
            'urgency_level' => $aiResponse['urgency_level'] ?? 'low',
        ]);

        return redirect()->route('symptoms.result', $symptomCheck->id);
    }

    public function result($id)
    {
        $symptomCheck = SymptomCheck::with('patient')->findOrFail($id);

        if ($symptomCheck->patient->user_id !== Auth::id()) {
            abort(403);
        }

        $specialization = $symptomCheck->ai_response['recommended_specialization'] ?? null;
        
        $recommendedDoctors = collect();
        if ($specialization && $symptomCheck->urgency_level !== 'high') { // Normally high urgency goes straight to emergencies!
            // Assuming specialization is stored in 'specialty' in Doctor model
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
