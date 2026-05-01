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

        // Find an available slot
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where('status', 'available')
            ->first();

        if (!$appointment) {
            return redirect()->back()->with('error', 'This slot is no longer available.');
        }

        $appointment->update([
            'patient_id' => $patient->id,
            'reason' => $request->reason,
            'status' => 'upcoming',
        ]);

        $doctor->user->notify(new \App\Notifications\AppointmentBooked($appointment));

        return redirect()->back()->with('success', 'Appointment booked successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        $patient = Patient::with('user')->where('user_id',Auth::id())->first();
        if ($appointment->patient_id !== $patient->id) {
            abort(403);
        }

        // Instead of marking as 'cancelled', we make it 'available' again for other patients
        // as per requirements: "show this appointment if cancelled"
        $appointment->update([
            'status' => 'available',
            'patient_id' => null,
            'reason' => null
        ]);
        
        if ($appointment->doctor && $appointment->doctor->user) {
            $appointment->doctor->user->notify(new \App\Notifications\AppointmentCancelled($appointment));
        }

        return redirect()->back()->with('success', 'Appointment cancelled successfully.');
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $patient = Patient::where('user_id',Auth::id())->first();
        if ($appointment->patient_id !== $patient->id) {
            abort(403);
        }

        // If date or time changed, we need to swap slots
        if ($appointment->appointment_date != $request->appointment_date || $appointment->appointment_time != $request->appointment_time) {
            $newSlot = Appointment::where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('appointment_time', $request->appointment_time)
                ->where('status', 'available')
                ->first();

            if (!$newSlot) {
                return redirect()->back()->with('error', 'The new slot is not available.');
            }

            // Make old slot available
            $appointment->update([
                'status' => 'available',
                'patient_id' => null,
                'reason' => null
            ]);

            // Take new slot
            $newSlot->update([
                'patient_id' => $patient->id,
                'reason' => $request->reason,
                'status' => 'upcoming'
            ]);
        } else {
            // Only update reason or doctor if time/date is same (though doctor change usually implies new slot)
            $appointment->update([
                'reason' => $request->reason,
                'doctor_id' => $request->doctor_id
            ]);
        }

        return redirect()->back()->with('success', 'Appointment updated successfully.');
    }
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date'
        ]);

        $slots = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->date)
            ->where('status', 'available')
            ->pluck('appointment_time');

        return response()->json($slots);
    }
}
