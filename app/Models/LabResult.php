<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    protected $fillable = [
        'patient_id',
        'test_name',
        'result_value',
        'unit',
        'reference_range',
        'status',
        'test_date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
