<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingNote extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'content',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
