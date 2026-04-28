<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * List all reports (medical histories) for the authenticated doctor.
     */
    public function index(Request $request)
    {
        $doctor = Doctor::where('user_id',Auth::id())->first();

        $baseQuery = MedicalHistory::with(['patient.user', 'prescription.items'])->whereHas('doctor',function ($q) {
            $q->where('user_id',Auth::id());
        });

        if ($request->filled('search')) {
            $search = $request->input('search');

            $baseQuery->where(function ($q) use ($search) {
                $q->where('condition', 'like', "%{$search}%")
                    ->orWhereHas('patient.user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $reports = $baseQuery
            ->latest('diagnosis_date')
            ->paginate(10)
            ->withQueryString();

        $totalReports = (clone $baseQuery)->count();
        $thisMonthReports = (clone $baseQuery)
            ->whereMonth('diagnosis_date', now()->month)
            ->whereYear('diagnosis_date', now()->year)
            ->count();
        $uniquePatients = (clone $baseQuery)->distinct('user_id')->count('user_id');

        $previousMonthReports = (clone $baseQuery)
            ->whereMonth('diagnosis_date', now()->subMonth()->month)
            ->whereYear('diagnosis_date', now()->subMonth()->year)
            ->count();

        $changePercentage = $previousMonthReports > 0
            ? round((($thisMonthReports - $previousMonthReports) / $previousMonthReports) * 100)
            : 0;

        $changeLabel = ($changePercentage >= 0 ? '+' : '') . $changePercentage . '%';

        $stats = [
            [
                'label' => 'Total Reports',
                'value' => $totalReports,
                'change' => $changeLabel,
                'icon' => 'fas fa-file-medical',
                'color' => 'bg-teal',
            ],
            [
                'label' => 'Reports This Month',
                'value' => $thisMonthReports,
                'change' => $changeLabel,
                'icon' => 'fas fa-calendar-check',
                'color' => 'bg-amber',
            ],
            [
                'label' => 'Unique Patients',
                'value' => $uniquePatients,
                'change' => '+0%',
                'icon' => 'fas fa-users',
                'color' => 'bg-rose',
            ],
        ];

        $patientsForSelect = Patient::with('user')->whereHas('appointments' , function ($q) use($doctor){
            $q->where('doctor_id',$doctor->id);
        })
            ->orderBy('user:name')
            ->get();

        return view('doctor.reports.index', compact('stats', 'reports', 'totalReports', 'patientsForSelect'));
    }

    /**
     * Show a single report, ensuring it belongs to one of the doctor's patients.
     */
    public function show(Request $request, MedicalHistory $history)
    {
        $role = 'doctor';
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
