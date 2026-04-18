<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Models\Appointment;
use App\Models\DoctorFeedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class FeedbackController extends Controller
{
    /**
     * Store new feedback for a completed appointment.
     */
    public function store(StoreFeedbackRequest $request): RedirectResponse
    {
        $appointment = Appointment::findOrFail($request->appointment_id);

        // Authorization via policy
        Gate::authorize('create', [DoctorFeedback::class, $appointment]);

        DoctorFeedback::create([
            'patient_id'     => auth()->user()->patient->id,
            'doctor_id'      => $appointment->doctor_id,
            'appointment_id' => $appointment->id,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }

    /**
     * Update patient's existing feedback.
     */
    public function update(UpdateFeedbackRequest $request, DoctorFeedback $feedback): RedirectResponse
    {
        Gate::authorize('update', $feedback);

        $feedback->update([
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Your review has been updated.');
    }

    /**
     * Delete patient's feedback.
     */
    public function destroy(DoctorFeedback $feedback): RedirectResponse
    {
        Gate::authorize('delete', $feedback);

        $feedback->delete();

        return back()->with('success', 'Your review has been removed.');
    }
}
