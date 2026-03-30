<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'title',
        'description',
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

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
