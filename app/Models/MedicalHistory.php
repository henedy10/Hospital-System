<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    protected $fillable = [
        'user_id',
        'condition',
        'diagnosis_date',
        'treatment',
        'doctor_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
