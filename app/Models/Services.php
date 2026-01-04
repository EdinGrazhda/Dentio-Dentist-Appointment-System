<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $fillable = [
        'service_name',
        'description',
        'price',
        'duration',
    ];


    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }



}
