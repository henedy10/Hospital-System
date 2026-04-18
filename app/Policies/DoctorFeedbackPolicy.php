<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\DoctorFeedback;
use App\Models\User;

class DoctorFeedbackPolicy
{
    /**
     * Patient can submit feedback only if:
     *  - the appointment belongs to them
     *  - the appointment is completed
     *  - no feedback has been submitted for this appointment yet
     */
    public function create(User $user, Appointment $appointment): bool
    {
        if (! $user->isPatient()) {
            return false;
        }

        $patient = $user->patient;

        return $patient
            && (int) $appointment->patient_id === (int) $patient->id
            && $appointment->status === 'completed'
            && $appointment->feedback === null;
    }

    /**
     * Patient can edit only their own feedback.
     */
    public function update(User $user, DoctorFeedback $feedback): bool
    {
        return $user->isPatient()
            && $user->patient
            && (int) $feedback->patient_id === (int) $user->patient->id;
    }

    /**
     * Patient can delete only their own feedback.
     */
    public function delete(User $user, DoctorFeedback $feedback): bool
    {
        return $this->update($user, $feedback);
    }

    /**
     * Doctor can reply only to feedback addressed to them.
     */
    public function reply(User $user, DoctorFeedback $feedback): bool
    {
        return $user->isDoctor()
            && $user->doctor
            && (int) $feedback->doctor_id === (int) $user->doctor->id;
    }
}
