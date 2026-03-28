<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = User::with('doctor')->where('id',Auth::id())->first();

        // Patients who have had at least one appointment with this doctor
        $myPatientsQuery = Patient::whereHas('appointments', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId->doctor->id);
            });

        // Stats: only for patients with appointments with this doctor
        $stats = [
            ['label' => 'My Patients', 'value' => (clone $myPatientsQuery)->count(), 'icon' => 'fas fa-users', 'color' => 'bg-teal'],
            ['label' => 'Seen This Month', 'value' => (clone $myPatientsQuery)->whereHas('appointments', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId)->whereMonth('appointment_date', now()->month)->whereYear('appointment_date', now()->year);
            })->count(), 'icon' => 'fas fa-user-plus', 'color' => 'bg-sky'],
            ['label' => 'Critical Cases', 'value' => '0', 'icon' => 'fas fa-exclamation-circle', 'color' => 'bg-red'],
        ];

        $query = Patient::whereHas('appointments', function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId->doctor->id);
                })
            ->with([
                'medicalHistories' => function ($q) {
                    $q->latest('diagnosis_date');
                },
                'user',
                'appointments' => function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId)->orderByDesc('appointment_date')->limit(1);
                }
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user',function ($q) use($search){
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Fetch patients from DB with pagination
        $patients = $query->paginate(12)->withQueryString();
        return view('doctor.patients.index', compact('patients', 'stats'));
    }

    public function show($id)
    {
        $doctor = User::with('doctor')->where('id', Auth::id())->first();

        $patient = Patient::where('user_id', $id)->whereHas('appointments', function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->doctor->id);
            })
            ->with([
                'medicalHistories' => function ($query) {
                    $query->with('doctor.user')->latest()->take(5);
                },
                'vitals' => function ($query) {
                    $query->latest()->take(10);
                },
                'user'
            ])
            ->first();

        return view('doctor.patients.show', compact('patient'));
    }
}
