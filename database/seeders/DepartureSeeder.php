<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departure;
use Carbon\Carbon;

class DepartureSeeder extends Seeder
{
    public function run()
    {
        $departures = [
            [
                'bus_id' => 1,
                'route' => 'Casablanca - Rabat',
                'scheduled_time' => Carbon::now()->addHours(2),
                'delayed_time' => null,
                'status' => 1, // A l'heure
                'prix' => 80.00,
                'places_disponibles' => 50,
            ],
            [
                'bus_id' => 2,
                'route' => 'Casablanca - Marrakech',
                'scheduled_time' => Carbon::now()->addHours(3),
                'delayed_time' => null,
                'status' => 1, // A l'heure
                'prix' => 120.00,
                'places_disponibles' => 45,
            ],
            [
                'bus_id' => 3,
                'route' => 'Casablanca - Agadir',
                'scheduled_time' => Carbon::now()->addHours(4),
                'delayed_time' => Carbon::now()->addHours(4)->addMinutes(30),
                'status' => 2, // En retard
                'prix' => 180.00,
                'places_disponibles' => 55,
            ],
            [
                'bus_id' => 5,
                'route' => 'Casablanca - Tanger',
                'scheduled_time' => Carbon::now()->addHours(5),
                'delayed_time' => null,
                'status' => 1, // A l'heure
                'prix' => 150.00,
                'places_disponibles' => 52,
            ],
        ];

        foreach ($departures as $departure) {
            Departure::create($departure);
        }
    }
}
