<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SymptomCheck extends Model
{
    protected $fillable = [
        'patient_id',
        'symptoms_json',
        'predicted_disease',
        'specialization',
        'urgency',
    ];

    protected $casts = [
        'symptoms_json' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
