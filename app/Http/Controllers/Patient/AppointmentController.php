<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Patient\AppointmentRequest;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'upcoming');

        $appointments = Auth::user()->appointments()
            ->where('status', $status)
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        $doctors = \App\Models\User::where('role', 'doctor')->get();

        return view('patient.appointments', compact('appointments', 'status', 'doctors'));
    }

    public function store(AppointmentRequest $request)
    {
        $doctor = \App\Models\User::findOrFail($request->doctor_id);

        Auth::user()->appointments()->create([
            'doctor_id' => $doctor->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
            'status' => 'upcoming',
        ]);

        return redirect()->back()->with('success', 'Appointment booked successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Appointment cancelled successfully.');
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $doctor = \App\Models\User::findOrFail($request->doctor_id);

        $appointment->update([
            'doctor_id' => $doctor->id,
            'doctor_name' => $doctor->name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Appointment updated successfully.');
    }
}
