<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'specialty',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedback()
    {
        return $this->hasMany(DoctorFeedback::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Average star rating (null if no reviews yet).
     */
    public function getAverageRatingAttribute(): ?float
    {
        $avg = $this->feedback()->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    /**
     * Total number of reviews.
     */
    public function getTotalReviewsAttribute(): int
    {
        return $this->feedback()->count();
    }
}
