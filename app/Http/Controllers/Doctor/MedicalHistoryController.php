<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'condition' => 'required|string|max:255',
            'diagnosis_date' => 'required|date',
            'treatment' => 'nullable|string',
        ]);

        MedicalHistory::create([
            'user_id' => $request->user_id,
            'condition' => $request->condition,
            'diagnosis_date' => $request->diagnosis_date,
            'treatment' => $request->treatment,
            'doctor_name' => Auth::user()->name,
        ]);

        return redirect()->back()->with('success', 'Medical record added successfully');
    }

    public function update(Request $request, MedicalHistory $history)
    {
        $request->validate([
            'condition' => 'required|string|max:255',
            'diagnosis_date' => 'required|date',
            'treatment' => 'nullable|string',
        ]);

        $history->update([
            'condition' => $request->condition,
            'diagnosis_date' => $request->diagnosis_date,
            'treatment' => $request->treatment,
        ]);

        return redirect()->back()->with('success', 'Medical record updated successfully');
    }

    public function destroy(MedicalHistory $history)
    {
        $history->delete();
        return redirect()->back()->with('success', 'Medical record deleted successfully');
    }
}
