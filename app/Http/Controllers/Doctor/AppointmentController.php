<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::whereHas('doctor.user' , function ($q){
            $q->where('id',Auth::id());
        })
            ->with('patient.user');

        if (!$request->filled('status')) {
            $query->where('status', '!=', 'available');
        }

        $query->latest();
        // Search by patient name
        if ($request->filled('search')) {
            $query->whereHas('patient.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->paginate(10);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request,Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:upcoming,completed,cancelled'
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        if ($appointment->patient && $appointment->patient->user) {
            $appointment->patient->user->notify(new \App\Notifications\AppointmentStatusUpdated($appointment));
        }

        return back()->with('success', 'Appointment status updated successfully');
    }
    public function storeAvailableSlots(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_times' => 'required|array',
            'appointment_times.*' => 'required|date_format:H:i'
        ]);

        $doctor = Auth::user()->doctor;

        foreach ($request->appointment_times as $time) {
            // Check if slot already exists
            $exists = Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', $request->appointment_date)
                ->where('appointment_time', $time)
                ->exists();

            if (!$exists) {
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => null,
                    'appointment_date' => $request->appointment_date,
                    'appointment_time' => $time,
                    'status' => 'available',
                    'reason' => null
                ]);
            }
        }

        return back()->with('success', 'Available slots created successfully.');
    }
}
