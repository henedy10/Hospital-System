<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_by',
        'patient_id',
        'title',
        'description',
        'notes',
        'due_at',
        'status',
        'category',
        'priority',
    ];

    protected $casts = [
        'due_at' => 'datetime',
    ];

    public function nurse()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Priority sort order: High > Medium > Low
     */
    public function getPriorityOrderAttribute(): int
    {
        return match ($this->priority) {
            'High'   => 1,
            'Medium' => 2,
            'Low'    => 3,
            default  => 4,
        };
    }
}
