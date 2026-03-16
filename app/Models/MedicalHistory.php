<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    protected $fillable = [
        'patient_id',
        'condition',
        'diagnosis_date',
        'treatment',
        'doctor_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
