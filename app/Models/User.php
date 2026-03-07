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
        'profile_image',
        'patient_id',
        'is_verified',
        'specialist',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if ($user->role === self::ROLE_PATIENT && !$user->patient_id) {
                $user->patient_id = 'PAT-' . strtoupper(now()->format('y')) . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }


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
     * Get the appointments for the user.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the medical histories for the user.
     */
    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    /**
     * Get the vitals for the user.
     */
    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }
}

