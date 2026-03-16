<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'gender',
        'dob',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'blood_type',
        'allergies',
        'insurance_provider',
        'insurance_member_id',
        'insurance_plan',
        'weight',
        'height',
    ];

    protected $casts = [
        'dob' => 'date',
        'allergies' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
