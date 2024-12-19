<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\User;
use App\Models\Departure;
use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Database\Seeders\AnnouncementSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\AdvertisementSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\BusSeeder;
use Database\Seeders\DepartureSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            AdvertisementSeeder::class,
            AnnouncementSeeder::class,
            BusSeeder::class,
            DepartureSeeder::class,
        ]);

        // Création de l'utilisateur de test
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Création des bus
        $bus1 = Bus::create([
            'numero' => 'BUS-001',
            'modele' => 'Mercedes Tourismo',
            'capacite' => 50,
            'annee' => 2020,
            'statut' => 'En service',
        ]);

        $bus2 = Bus::create([
            'numero' => 'BUS-002',
            'modele' => 'Volvo 9700',
            'capacite' => 45,
            'annee' => 2021,
            'statut' => 'En service',
        ]);

        $bus3 = Bus::create([
            'numero' => 'BUS-003',
            'modele' => 'Scania Touring',
            'capacite' => 55,
            'annee' => 2019,
            'statut' => 'En maintenance',
        ]);

        // Création des départs
        $departure1 = Departure::create([
            'bus_id' => $bus1->id,
            'route' => 'Paris - Lyon',
            'scheduled_time' => Carbon::now()->addDays(1),
            'status' => '1',
            'prix' => 50.00,
            'places_disponibles' => 50,
        ]);

        $departure2 = Departure::create([
            'bus_id' => $bus2->id,
            'route' => 'Lyon - Marseille',
            'scheduled_time' => Carbon::now()->addDays(2),
            'status' => '1',
            'prix' => 40.00,
            'places_disponibles' => 45,
        ]);

        $departure3 = Departure::create([
            'bus_id' => $bus1->id,
            'route' => 'Marseille - Nice',
            'scheduled_time' => Carbon::now()->addDays(3),
            'status' => '2',
            'delayed_time' => Carbon::now()->addDays(3)->addHours(1),
            'prix' => 35.00,
            'places_disponibles' => 50,
        ]);

        // Création des réservations
        Reservation::create([
            'departure_id' => $departure1->id,
            'nom_client' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'nombre_places' => 2,
            'statut' => 'confirmé',
            'prix_total' => 100.00,
            'siege_numeros' => json_encode([1, 2])
        ]);

        Reservation::create([
            'departure_id' => $departure2->id,
            'nom_client' => 'Marie Martin',
            'email' => 'marie.martin@example.com',
            'nombre_places' => 1,
            'statut' => 'confirmé',
            'prix_total' => 40.00,
            'siege_numeros' => json_encode([5])
        ]);

        Reservation::create([
            'departure_id' => $departure3->id,
            'nom_client' => 'Pierre Durand',
            'email' => 'pierre.durand@example.com',
            'nombre_places' => 3,
            'statut' => 'en_attente',
            'prix_total' => 105.00,
            'siege_numeros' => json_encode([10, 11, 12])
        ]);
    }
}
