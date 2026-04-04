<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    protected $fillable = [
        'user_id',
        'speciality',
        'bio',
        'department',
        'shift',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recordedVitals()
    {
        return $this->hasMany(Vital::class, 'recorded_by');
    }

    public function assignedPatients()
    {
        // If patients are assigned to the user_id (nurse user), we can still use User model
        // but it might be better to link them to the Nurse model if we change the patients table later.
        // For now, patients are linked to users.role = nurse via nurse_id in patients table.
        return $this->user->assignedPatients();
    }
}
