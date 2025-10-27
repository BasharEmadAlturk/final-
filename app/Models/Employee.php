<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    // use SoftDeletes;

    protected $guarded = [];


    protected $casts = [
        'days' => 'array',
        'social' => 'array',
    ];


    public function holidays()
    {
        return $this->hasMany(Holiday::class,'employee_id');
    }


    public function services()
    {
        return $this->belongsToMany(Service::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}
