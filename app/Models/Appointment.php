<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AppointmentStatus;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'dentist_id',
        'service_id',
        'patient_name',
        'email',
        'phone',
        'appointment_date',
        'status',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'status' => AppointmentStatus::class,
    ];

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }
}
