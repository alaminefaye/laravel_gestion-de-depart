<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Données de test pour les départs
    $departures = [
        (object)[
            'destination' => 'YOP-YAKRO-BOUAKE',
            'departure_time' => '06:30',
            'status' => 'À l\'heure'
        ],
        (object)[
            'destination' => 'YAKRO-BOUAKE',
            'departure_time' => '09:30',
            'status' => 'À l\'heure'
        ],
        (object)[
            'destination' => 'YAKRO-ABIDJAN',
            'departure_time' => '07:30',
            'status' => 'Retardé',
            'new_time' => '08:00'
        ]
    ];
    
    return view('welcome', ['departures' => $departures]);
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
