<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DoctorFeedback extends Model
{
    protected $table = 'doctor_feedback';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_id',
        'rating',
        'comment',
        'doctor_reply',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeSortBy(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'highest' => $query->orderByDesc('rating'),
            'lowest'  => $query->orderBy('rating'),
            default   => $query->latest(),   // 'latest' or null
        };
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Return an array of filled/empty star booleans for Blade rendering.
     * e.g. rating=3 → [true, true, true, false, false]
     */
    public function getStarsAttribute(): array
    {
        return array_map(fn($i) => $i <= $this->rating, range(1, 5));
    }

    public function isLowRating(): bool
    {
        return $this->rating <= 2;
    }
}
