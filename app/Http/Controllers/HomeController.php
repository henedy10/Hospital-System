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
            ->get();

        $doctors = User::where('role', 'doctor')->take(3)->get();

        return view('welcome', compact('totalDoctors', 'totalPatients', 'specialties', 'doctors'));
    }

    public function showSpecialty(Request $request, $specialty)
    {
        $query = Doctor::with(['user', 'feedback.patient.user'])
            ->withAvg('feedback', 'rating')
            ->where('specialty', $specialty);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('experience')) {
            $exp = $request->experience;
            if ($exp === '1-5') $query->whereBetween('experience_years', [1, 5]);
            elseif ($exp === '6-10') $query->whereBetween('experience_years', [6, 10]);
            elseif ($exp === '10+') $query->where('experience_years', '>=', 10);
        }

        $doctors = $query->get();

        if ($request->filled('rating')) {
            $minRating = (float) $request->rating;
            $doctors = $doctors->filter(function ($doctor) use ($minRating) {
                return ($doctor->average_rating ?? 0) >= $minRating;
            });
        }

        $otherSpecialties = Doctor::select('specialty')
            ->whereNotNull('specialty')
            ->where('specialty', '!=', $specialty)
            ->distinct()
            ->take(4)
            ->get();

        return view('specialty', compact('specialty', 'doctors', 'otherSpecialties'));
    }
}
