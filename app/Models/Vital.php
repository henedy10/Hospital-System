<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    protected $fillable = [
        'user_id',
        'blood_pressure',
        'heart_rate',
        'temperature',
        'respiratory_rate',
        'recorded_by',
        'spo2',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class, 'recorded_by', 'id');
    }
}
