<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Patient\AppointmentRequest;
use App\Models\
{
    Appointment,
    Doctor,
    User,
    Patient
};


class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status ?? 'upcoming';
        $appointments = Appointment::whereHas('patient' , function ($q){
            $q->where('user_id',Auth::id());
        })
            ->with(['doctor.user', 'feedback'])
            ->where('status', $status)
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();
        $doctors = Doctor::with('user')->whereHas('user',function ($q) {
            $q->where('role','doctor');
        })->get();

        return view('patient.appointments', compact('appointments', 'status', 'doctors'));
    }

    public function store(AppointmentRequest $request)
    {
        $doctor = Doctor::with('user')->findOrFail($request->doctor_id);
        $patient = Patient::where('user_id',Auth::id())->first();

        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
            'status' => 'upcoming',
        ]);

        $doctor->user->notify(new \App\Notifications\AppointmentBooked($appointment));

        return redirect()->back()->with('success', 'Appointment booked successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        $patient = Patient::with('user')->where('user_id',Auth::id())->first();
        if ($patient->user->id !== Auth::id()) {
            abort(403);
        }

        $appointment->update(['status' => 'cancelled']);
        
        if ($appointment->doctor && $appointment->doctor->user) {
            $appointment->doctor->user->notify(new \App\Notifications\AppointmentCancelled($appointment));
        }

        return redirect()->back()->with('success', 'Appointment cancelled successfully.');
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $patient = Patient::with('user')->where('user_id',Auth::id())->first();
        if ($patient->user->id !== Auth::id()) {
            abort(403);
        }

        $doctor = Doctor::findOrFail($request->doctor_id);

        $appointment->update([
            'doctor_id' => $doctor->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Appointment updated successfully.');
    }
}
