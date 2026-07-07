<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::whereHas('doctor.user', function ($q) {
            $q->where('id', Auth::id());
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

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:upcoming,completed,cancelled'
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        $suggestedSlots = collect([]);
        if ($request->status == 'cancelled') {
            $now = now();
            $suggestedSlots = Appointment::where('doctor_id', $appointment->doctor_id)
                ->where('status', 'available')
                ->where(function ($query) use ($now) {
                    $query->where('appointment_date', '>', $now->toDateString())
                        ->orWhere(function ($q) use ($now) {
                            $q->where('appointment_date', '=', $now->toDateString())
                                ->where('appointment_time', '>=', $now->toTimeString());
                        });
                })
                ->orderBy('appointment_date', 'asc')
                ->orderBy('appointment_time', 'asc')
                ->limit(3)
                ->get();

            $suggestedArray = $suggestedSlots->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'appointment_date' => $slot->appointment_date,
                    'appointment_time' => $slot->appointment_time,
                ];
            })->toArray();

            $response = Http::post('https://finicky-unstuffed-rewrap.ngrok-free.dev/webhook-test/4672b0fe-7548-4e59-8904-9887cd53abbb', [
                'type' => 'cancelling',
                'id' => $appointment->id,
                'doctor_name' => $appointment->doctor->user->name,
                'doctor_specialty' => $appointment->doctor->specialty,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'patient_email' => $appointment->patient->user->email,
                'patient_name' => $appointment->patient->user->name,
                'from' => 'doctor',
                'suggested_appointments' => $suggestedArray
            ]);
        }

        if ($appointment->patient && $appointment->patient->user) {
            $appointment->patient->user->notify(new \App\Notifications\AppointmentStatusUpdated($appointment, $suggestedSlots));
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
