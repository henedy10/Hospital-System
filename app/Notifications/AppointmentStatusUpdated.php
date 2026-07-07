<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentStatusUpdated extends Notification
{
    use Queueable;

    public $appointment;
    public $suggestedSlots;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment, $suggestedSlots = null)
    {
        $this->appointment = $appointment;
        $this->suggestedSlots = $suggestedSlots;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = ucfirst($this->appointment->status);
        $data = [
            'appointment_id' => $this->appointment->id,
            'message' => 'Your appointment on ' . $this->appointment->appointment_date . ' has been ' . $status . '.',
            'url' => route('patient.appointments'),
        ];

        if ($this->appointment->status === 'cancelled' && $this->suggestedSlots && $this->suggestedSlots->isNotEmpty()) {
            $slotsArray = $this->suggestedSlots->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'appointment_date' => $slot->appointment_date,
                    'appointment_time' => $slot->appointment_time,
                ];
            })->toArray();
            $data['suggested_appointments'] = $slotsArray;

            $slotsText = $this->suggestedSlots->map(function ($slot) {
                return $slot->appointment_date . ' ' . $slot->appointment_time;
            })->implode(', ');
            $data['message'] .= ' Nearest alternative slots: ' . $slotsText . '.';
        }

        return $data;
    }
}
