<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    protected $fillable = [
        'dentist_id',
        'service_id',
        'patient_name',
        'appointment_date',
        'status',
    ];

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }
    public function service()
    {
        return $this->belongsTo(Services::class);
    }
}
