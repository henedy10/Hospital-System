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
        'weight',
        'height',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
