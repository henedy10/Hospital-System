<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VitalsController extends Controller
{
    public function create($patientId)
    {
        return view('nurse.vitals.create', compact('patientId'));
    }

    public function store(Request $request)
    {
        // Imagine validation and DB logic here
        return redirect()->route('nurse.patients.show', $request->patient_id)
            ->with('success', 'Vitals recorded successfully.');
    }
}
