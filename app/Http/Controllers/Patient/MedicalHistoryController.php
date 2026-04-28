<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalHistory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryController extends Controller
{
    public function index()
    {
        $patient = Patient::with('user')->where('user_id' , Auth::id())->first();

        $history = MedicalHistory::with(['patient.user','doctor.user', 'prescription'])
            ->where('patient_id',$patient->id)
            ->orderBy('diagnosis_date', 'desc')
            ->get();


        return view('patient.history', compact('history','patient'));
    }

    public function show(Request $request, MedicalHistory $history)
    {
            $history->load(['patient.user', 'user.vitals', 'doctor.user', 'prescription.items']);

        // $latestVital = $user->vitals()->latest()->first();

        // $vitals = [];

        // if ($latestVital) {
        //     $vitals = [
        //         [
        //             'label' => 'Blood Pressure',
        //             'value' => $latestVital->blood_pressure ?? '—',
        //             'status' => 'Normal',
        //         ],
        //         [
        //             'label' => 'Heart Rate',
        //             'value' => $latestVital->heart_rate ? $latestVital->heart_rate . ' bpm' : '—',
        //             'status' => 'Normal',
        //         ],
        //         [
        //             'label' => 'Temperature',
        //             'value' => $latestVital->temperature ? $latestVital->temperature . ' °C' : '—',
        //             'status' => 'Normal',
        //         ],
        //     ];
        // }
        $role = 'patient';
        $report = [
            'id' => $history->id,
            'name' => $history->condition,
            'date' => optional($history->diagnosis_date)->format('Y-m-d') ?? $history->created_at->format('Y-m-d'),
            'category' => 'Clinical Report',
            'department_en' => ucfirst($history->doctor->specialty) . ' Clinical' ?: 'Attending Physician',
            'status' => 'Recorded',
            'patient' => [
                'name' => $history->patient->user->name,
                'id' => optional($history->patient)->patient_id ?? 'N/A',
                'age' => $history->patient->dob ? $history->patient->dob->age : null,
                'weight' => $history->patient->weight ? $history->patient->weight . ' kg' : 'N/A',
                'blood_type' => $history->patient->blood_type ?? 'N/A',
                'avatar' => $history->patient->user->profile_image
                    ? asset('storage/' . $history->patient->user->profile_image)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($history->patient->user->name) . '&background=0D9488&color=fff',
            ],
            'diagnosis' => $history->condition,
            // 'vitals' => $vitals,
            'clinical_notes' => $history->treatment,
            'treatment_plan' => $history->treatment
                ? preg_split("/\r\n|\n|\r/", $history->treatment)
                : [],
            'doctor' => $history->doctor,
            'prescription' => $history->prescription,
        ];

        return view('doctor.reports.show', compact('report','role'));
    }
}
