<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;

class BusSeeder extends Seeder
{
    public function run()
    {
        $buses = [
            [
                'numero' => 'BUS001',
                'modele' => 'Mercedes-Benz Tourismo',
                'capacite' => 50,
                'annee' => 2022,
                'statut' => 'En service',
            ],
            [
                'numero' => 'BUS002',
                'modele' => 'Volvo 9900',
                'capacite' => 45,
                'annee' => 2021,
                'statut' => 'En service',
            ],
            [
                'numero' => 'BUS003',
                'modele' => 'Setra ComfortClass',
                'capacite' => 55,
                'annee' => 2023,
                'statut' => 'En service',
            ],
            [
                'numero' => 'BUS004',
                'modele' => 'MAN Lion\'s Coach',
                'capacite' => 48,
                'annee' => 2022,
                'statut' => 'En maintenance',
            ],
            [
                'numero' => 'BUS005',
                'modele' => 'Neoplan Cityliner',
                'capacite' => 52,
                'annee' => 2021,
                'statut' => 'En service',
            ],
        ];

        foreach ($buses as $bus) {
            Bus::create($bus);
        }
    }
}
