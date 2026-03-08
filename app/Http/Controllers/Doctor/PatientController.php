<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        // Stats for the patients page
        $stats = [
            ['label' => 'Total Patients', 'value' => User::where('role', User::ROLE_PATIENT)->count(), 'icon' => 'fas fa-users', 'color' => 'bg-teal'],
            ['label' => 'New Patients (This Month)', 'value' => User::where('role', User::ROLE_PATIENT)->whereMonth('created_at', now()->month)->count(), 'icon' => 'fas fa-user-plus', 'color' => 'bg-sky'],
            ['label' => 'Critical Cases', 'value' => '0', 'icon' => 'fas fa-exclamation-circle', 'color' => 'bg-red'],
        ];

        $query = User::where('role', User::ROLE_PATIENT)
            ->with([
                'medicalHistories' => function ($q) {
                    $q->latest('diagnosis_date');
                },
                'patient'
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
        $patient = User::where('role', User::ROLE_PATIENT)
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
