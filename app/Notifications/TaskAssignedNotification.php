<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $doctor = $this->task->assignedBy;
        $doctorName = $doctor ? $doctor->name : 'A doctor';

        $priorityEmoji = match ($this->task->priority) {
            'High'   => '🔴',
            'Medium' => '🟡',
            'Low'    => '🔵',
            default  => '⚪',
        };

        return [
            'task_id'  => $this->task->id,
            'message'  => "{$priorityEmoji} {$doctorName} assigned you a new task: \"{$this->task->title}\" — due " .
                          \Carbon\Carbon::parse($this->task->due_at)->format('M d, h:i A'),
            'priority' => $this->task->priority,
            'url'      => route('nurse.tasks'),
        ];
    }
}
