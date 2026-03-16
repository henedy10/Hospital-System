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
            ->with('patient.user')
            ->latest();
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

    public function updateStatus(Request $request, \App\Models\Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
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
