<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = Auth::id();

        // Patients who have had at least one appointment with this doctor
        $myPatientsQuery = User::where('role', User::ROLE_PATIENT)
            ->whereHas('appointments', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            });

        // Stats: only for patients with appointments with this doctor
        $stats = [
            ['label' => 'My Patients', 'value' => (clone $myPatientsQuery)->count(), 'icon' => 'fas fa-users', 'color' => 'bg-teal'],
            ['label' => 'Seen This Month', 'value' => (clone $myPatientsQuery)->whereHas('appointments', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId)->whereMonth('appointment_date', now()->month)->whereYear('appointment_date', now()->year);
            })->count(), 'icon' => 'fas fa-user-plus', 'color' => 'bg-sky'],
            ['label' => 'Critical Cases', 'value' => '0', 'icon' => 'fas fa-exclamation-circle', 'color' => 'bg-red'],
        ];

        $query = User::where('role', User::ROLE_PATIENT)
            ->whereHas('appointments', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })
            ->with([
                'medicalHistories' => function ($q) {
                    $q->latest('diagnosis_date');
                },
                'patient',
                'appointments' => function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId)->orderByDesc('appointment_date')->limit(1);
                }
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($q) use ($search) {
                        $q->where('patient_id', 'like', "%{$search}%");
                    })
                    ->orWhereHas('medicalHistories', function ($q) use ($search) {
                        $q->where('condition', 'like', "%{$search}%");
                    });
            });
        }

        // Fetch patients from DB with pagination
        $patients = $query->paginate(12)->withQueryString();

        return view('doctor.patients.index', compact('patients', 'stats'));
    }

    public function show($id)
    {
        $doctorId = Auth::id();

        $patient = User::where('role', User::ROLE_PATIENT)
            ->whereHas('appointments', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })
            ->with([
                'medicalHistories' => function ($query) {
                    $query->latest('diagnosis_date');
                },
                'vitals' => function ($query) {
                    $query->latest()->take(10);
                }
            ])
            ->findOrFail($id);

        return view('doctor.patients.show', compact('patient'));
    }
}
