<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departure extends Model
{
    protected $fillable = [
        'route',
        'scheduled_time',
        'actual_time',
        'status'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'actual_time' => 'datetime',
    ];
}
