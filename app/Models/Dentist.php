<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'years_of_experience',
        'specialization',
        'image_path',
        'work_start_time',
        'work_end_time',
        'slot_duration',
    ];

    protected $casts = [
        'work_start_time' => 'datetime:H:i',
        'work_end_time' => 'datetime:H:i',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    
    
}
