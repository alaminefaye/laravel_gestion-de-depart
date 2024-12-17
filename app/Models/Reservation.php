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
        'telephone',
        'nombre_places',
        'statut', // Confirmé, En attente, Annulé
        'reference',
        'montant_total'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            $reservation->reference = 'RES-' . strtoupper(uniqid());
        });
    }
}
