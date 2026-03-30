<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'profile_image',
        'department',
        'shift',
        'is_verified',
        'notification_settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_settings' => 'array',
        ];
    }

    // Role Constants
    const ROLE_ADMIN = 'admin';
    const ROLE_PATIENT = 'patient';
    const ROLE_DOCTOR = 'doctor';
    const ROLE_NURSE = 'nurse';

    // Role Helpers
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isPatient()
    {
        return $this->role === self::ROLE_PATIENT;
    }
    public function isDoctor()
    {
        return $this->role === self::ROLE_DOCTOR;
    }
    public function isNurse()
    {
        return $this->role === self::ROLE_NURSE;
    }

    /**
     * Get the patient profile for the user.
     */
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Get the doctor profile for the user.
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Get the appointments for the user (patient).
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    /**
     * Get the medical histories for the user.
     */
    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    /**
     * Get the appointments for the doctor.
     */
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Get the vitals for the user.
     */
    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }

    /**
     * Get the tasks for the nurse.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    /**
     * Get the patients assigned to the nurse.
     */
    public function assignedPatients()
    {
        return $this->hasMany(Patient::class, 'nurse_id');
    }
}

