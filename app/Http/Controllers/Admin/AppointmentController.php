<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $doctorId = $request->query('doctor_id', '');
        $date = $request->query('date', '');

        $query = Appointment::with(['user', 'doctor.user'])
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($doctorId) {
            $query->whereHas('doctor', function ($query) use ($doctorId) {
                $query->where('user_id', $doctorId);
            });
        }

        if ($date) {
            $query->whereDate('appointment_date', $date);
        }

        $appointments = $query->paginate(20)->withQueryString();

        $doctors = User::where('role', User::ROLE_DOCTOR)->orderBy('name')->get();

        $counts = [
            'all' => Appointment::count(),
            'upcoming' => Appointment::where('status', 'upcoming')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'status', 'doctorId', 'date', 'doctors', 'counts'));
    }
}
