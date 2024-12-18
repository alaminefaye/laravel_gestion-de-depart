<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure_id',
        'nom_client',
        'email',
        'nombre_places',
        'prix_total',
        'statut',
        'siege_numeros',
        'user_id',
        'reference'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'prix_total' => 'decimal:2',
        'siege_numeros' => 'json'
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            $reservation->reference = 'RES-' . strtoupper(uniqid());
        });
    }
}
