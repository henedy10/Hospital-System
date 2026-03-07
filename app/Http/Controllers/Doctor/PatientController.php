<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PatientController extends Controller
{
    public function index()
    {
        // Stats for the patients page
        $stats = [
            ['label' => 'إجمالي المرضى', 'value' => User::where('role', User::ROLE_PATIENT)->count(), 'icon' => 'fas fa-users', 'color' => 'bg-teal'],
            ['label' => 'مرضى جدد (هذا الشهر)', 'value' => User::where('role', User::ROLE_PATIENT)->whereMonth('created_at', now()->month)->count(), 'icon' => 'fas fa-user-plus', 'color' => 'bg-sky'],
            ['label' => 'حالات حرجة', 'value' => '0', 'icon' => 'fas fa-exclamation-circle', 'color' => 'bg-red'],
        ];

        // Fetch patients from DB
        $patients = User::where('role', User::ROLE_PATIENT)
            ->with([
                'medicalHistories' => function ($query) {
                    $query->latest('diagnosis_date');
                }
            ])
            ->get();

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
