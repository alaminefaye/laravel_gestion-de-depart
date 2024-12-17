<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'modele',
        'capacite',
        'annee',
        'statut'
    ];

    protected $casts = [
        'derniere_maintenance' => 'datetime',
        'prochaine_maintenance' => 'datetime',
    ];

    public function departures()
    {
        return $this->hasMany(Departure::class);
    }

    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, Departure::class);
    }
}
