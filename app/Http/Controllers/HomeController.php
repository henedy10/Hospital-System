<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the landing page with dynamic statistics.
     */
    public function index()
    {
        $totalDoctors = User::where('role', 'doctor')->count();
        $totalPatients = User::where('role', 'patient')->count();
        $specialties = Doctor::select('specialty')
            ->whereNotNull('specialty')
            ->distinct()
            ->take(3)
            ->get();

        $doctors = User::where('role', 'doctor')->take(3)->get();

        return view('welcome', compact('totalDoctors', 'totalPatients', 'specialties', 'doctors'));
    }

    public function showSpecialty($specialty)
    {
        $doctors = Doctor::with(['user', 'feedback.patient.user'])->where('specialty', $specialty)->get();
        $otherSpecialties = Doctor::select('specialty')
            ->whereNotNull('specialty')
            ->where('specialty', '!=', $specialty)
            ->distinct()
            ->take(4)
            ->get();

        return view('specialty', compact('specialty', 'doctors', 'otherSpecialties'));
    }
}
