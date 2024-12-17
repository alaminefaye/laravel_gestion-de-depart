<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\User;
use App\Models\Departure;
use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Database\Seeders\AnnouncementSeeder;
use Database\Seeders\AdminUserSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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
            'statut' => 'En service',
            'derniere_maintenance' => Carbon::now()->subMonths(2),
            'prochaine_maintenance' => Carbon::now()->addMonths(1),
        ]);

        $bus2 = Bus::create([
            'numero' => 'BUS-002',
            'modele' => 'Volvo 9700',
            'capacite' => 45,
            'statut' => 'En service',
            'derniere_maintenance' => Carbon::now()->subMonths(1),
            'prochaine_maintenance' => Carbon::now()->addMonths(2),
        ]);

        $bus3 = Bus::create([
            'numero' => 'BUS-003',
            'modele' => 'Scania Touring',
            'capacite' => 55,
            'statut' => 'En maintenance',
            'derniere_maintenance' => Carbon::now()->subMonths(6),
            'prochaine_maintenance' => Carbon::now(),
        ]);

        // Création des départs
        $departure1 = Departure::create([
            'route' => 'YAKRO-BOUAKE',
            'scheduled_time' => Carbon::tomorrow()->setHour(8)->setMinute(0),
            'bus_id' => $bus1->id,
            'places_disponibles' => 50,
            'prix' => 5000,
            'status' => 'On time',
            'taux_occupation' => 0,
        ]);

        $departure2 = Departure::create([
            'route' => 'BOUAKE-YAKRO',
            'scheduled_time' => Carbon::tomorrow()->setHour(14)->setMinute(0),
            'bus_id' => $bus2->id,
            'places_disponibles' => 45,
            'prix' => 5000,
            'status' => 'On time',
            'taux_occupation' => 0,
        ]);

        $departure3 = Departure::create([
            'route' => 'YAKRO-ABIDJAN',
            'scheduled_time' => Carbon::tomorrow()->setHour(16)->setMinute(0),
            'bus_id' => $bus1->id,
            'places_disponibles' => 50,
            'prix' => 7000,
            'status' => 'Delayed',
            'taux_occupation' => 0,
        ]);

        // Création des réservations
        $reservation1 = Reservation::create([
            'departure_id' => $departure1->id,
            'reference' => 'RES-' . strtoupper(uniqid()),
            'nom_client' => 'John Doe',
            'email' => 'john@example.com',
            'telephone' => '0123456789',
            'nombre_places' => 2,
            'montant_total' => 10000,
            'statut' => 'Confirmé',
        ]);

        $reservation2 = Reservation::create([
            'departure_id' => $departure2->id,
            'reference' => 'RES-' . strtoupper(uniqid()),
            'nom_client' => 'Jane Smith',
            'email' => 'jane@example.com',
            'telephone' => '0987654321',
            'nombre_places' => 1,
            'montant_total' => 5000,
            'statut' => 'Confirmé',
        ]);

        // Création des annonces
        $this->call([
            AdminUserSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
