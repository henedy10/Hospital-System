<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\MedicalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryController extends Controller
{
    public function store(Request $request)
    {
        $doctor = Doctor::where('user_id',Auth::id())->first();
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'condition' => 'required|string|max:255',
            'diagnosis_date' => 'required|date',
            'treatment' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.medicine_name' => 'required_with:items|string|max:255',
            'items.*.dosage' => 'required_with:items|string|max:100',
            'items.*.frequency' => 'required_with:items|integer|min:1|max:10',
            'items.*.duration' => 'required_with:items|integer|min:1|max:365',
            'items.*.instructions' => 'nullable|string|max:500',
            'prescription_notes' => 'nullable|string|max:1000',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $doctor) {
            $history = MedicalHistory::create([
                'patient_id' => $request->patient_id,
                'condition' => $request->condition,
                'diagnosis_date' => $request->diagnosis_date,
                'treatment' => $request->treatment,
                'doctor_id' => $doctor->id,
            ]);

            if ($request->has('items') && is_array($request->items) && count($request->items) > 0) {
                $prescription = \App\Models\Prescription::create([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $doctor->id,
                    'medical_history_id' => $history->id,
                    'notes' => $request->prescription_notes,
                ]);

                foreach ($request->items as $itemData) {
                    \App\Models\PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_name' => $itemData['medicine_name'],
                        'dosage' => $itemData['dosage'],
                        'frequency' => $itemData['frequency'],
                        'duration' => $itemData['duration'],
                        'instructions' => $itemData['instructions'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Medical record and prescription added successfully');
    }

    public function update(Request $request, MedicalHistory $history)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();
        
        $request->validate([
            'condition' => 'required|string|max:255',
            'diagnosis_date' => 'required|date',
            'treatment' => 'nullable|string',
            'prescription_notes' => 'nullable|string|max:1000',
            'items' => 'nullable|array',
            'items.*.medicine_name' => 'required_with:items|string|max:255',
            'items.*.dosage' => 'required_with:items|string|max:100',
            'items.*.frequency' => 'required_with:items|integer|min:1|max:10',
            'items.*.duration' => 'required_with:items|integer|min:1|max:365',
            'items.*.instructions' => 'nullable|string|max:500',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $history, $doctor) {
            $history->update([
                'condition' => $request->condition,
                'diagnosis_date' => $request->diagnosis_date,
                'treatment' => $request->treatment,
            ]);

            // Handle Prescription
            if ($request->has('items') && is_array($request->items) && count($request->items) > 0) {
                $prescription = $history->prescription;
                
                if (!$prescription) {
                    $prescription = \App\Models\Prescription::create([
                        'patient_id' => $history->patient_id,
                        'doctor_id' => $doctor->id,
                        'medical_history_id' => $history->id,
                        'notes' => $request->prescription_notes,
                    ]);
                } else {
                    $prescription->update(['notes' => $request->prescription_notes]);
                    // Delete old items to recreate them
                    $prescription->items()->delete();
                }

                foreach ($request->items as $itemData) {
                    \App\Models\PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_name' => $itemData['medicine_name'],
                        'dosage' => $itemData['dosage'],
                        'frequency' => $itemData['frequency'],
                        'duration' => $itemData['duration'],
                        'instructions' => $itemData['instructions'] ?? null,
                    ]);
                }
            } else {
                // If no items provided but a prescription existed, we might want to delete it or just keep it.
                // It's safer to leave it or let them explicitly delete it. 
                // But for a full sync, if they remove all items, delete the prescription.
                if ($history->prescription) {
                    $history->prescription->delete();
                }
            }
        });

        return redirect()->back()->with('success', 'Medical record updated successfully');
    }

    public function destroy(MedicalHistory $history)
    {
        $history->delete();
        return redirect()->back()->with('success', 'Medical record deleted successfully');
    }
}
