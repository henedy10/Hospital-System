<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SymptomCheck extends Model
{
    protected $fillable = [
        'patient_id',
        'symptoms_text',
        'ai_response',
        'urgency_level',
    ];

    protected $casts = [
        'ai_response' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
