<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryController extends Controller
{
    public function index()
    {
        $history = Auth::user()->medicalHistories()
            ->orderBy('diagnosis_date', 'desc')
            ->get();

        return view('patient.history', compact('history'));
    }
}
