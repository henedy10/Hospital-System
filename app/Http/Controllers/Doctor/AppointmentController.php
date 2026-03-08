<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->doctorAppointments()
            ->with('user')
            ->latest();

        // Search by patient name
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
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

        $appointments = $query->paginate(10)->withQueryString();

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request, \App\Models\Appointment $appointment)
    {
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:upcoming,completed,cancelled'
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Appointment status updated successfully');
    }
}
