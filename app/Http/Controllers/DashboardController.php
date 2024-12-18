<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Departure;
use App\Models\Reservation;
use App\Models\Advertisement;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_voyages' => Departure::count(),
            'voyages_aujourdhui' => Departure::whereDate('scheduled_time', Carbon::today())->count(),
            'reservations_mois' => Reservation::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
            'revenu_mois' => Reservation::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->where('statut', 'Confirmé')
                ->sum('prix_total'),
            'destinations_populaires' => Departure::select('route', DB::raw('count(*) as total'))
                ->groupBy('route')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->pluck('total', 'route')
                ->toArray()
        ];

        // Prochains départs
        $departures = Departure::with(['bus', 'reservations'])
            ->whereDate('scheduled_time', '>=', Carbon::today())
            ->orderBy('scheduled_time')
            ->get();

        // Liste des bus
        $buses = Bus::all();

        // État des bus
        $bus_stats = [
            'total' => Bus::count(),
            'en_service' => Bus::where('statut', 'En service')->count(),
            'en_maintenance' => Bus::where('statut', 'En maintenance')->count(),
        ];

        // Dernières réservations
        $recent_reservations = Reservation::with('departure')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Récupérer les départs, publicités et annonces
        $all_departures = Departure::orderBy('scheduled_time', 'asc')->get();
        $advertisements = Advertisement::orderBy('created_at', 'desc')->get();
        $announcements = Announcement::orderBy('created_at', 'desc')->get();

        return view('dashboard.index', compact(
            'stats',
            'departures',
            'buses',
            'bus_stats',
            'recent_reservations',
            'all_departures',
            'advertisements',
            'announcements'
        ));
    }

    public function storeDeparture(Request $request)
    {
        $validated = $request->validate([
            'route' => 'required|string',
            'scheduled_time' => 'required|date',
            'bus_id' => 'required|exists:buses,id',
            'prix' => 'required|numeric|min:0',
            'places_disponibles' => 'required|integer|min:1'
        ]);

        // Initialiser le taux d'occupation à 0
        $validated['taux_occupation'] = 0;

        Departure::create($validated);

        return redirect()->back()->with('success', 'Nouveau départ ajouté avec succès!');
    }

    public function updateDepartureStatus(Request $request, $id)
    {
        $departure = Departure::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:À l\'heure,Retardé,Annulé',
            'actual_time' => 'required_if:status,Retardé|nullable|date'
        ]);

        $departure->update($validated);

        if ($departure->status === 'Annulé') {
            // Notifier les passagers de l'annulation
            foreach ($departure->reservations as $reservation) {
                // Implement notification logic here
            }
        }

        return redirect()->back()->with('success', 'Statut mis à jour avec succès!');
    }

    public function manageBuses()
    {
        $buses = Bus::orderBy('numero')->get();
        return view('dashboard.buses', compact('buses'));
    }

    public function storeBus(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string|unique:buses,numero',
            'modele' => 'required|string',
            'capacite' => 'required|integer|min:1',
        ]);

        $bus = Bus::create([
            'numero' => $validated['numero'],
            'modele' => $validated['modele'],
            'capacite' => $validated['capacite'],
            'statut' => 'En service',
        ]);

        return redirect()->route('dashboard.buses.index')->with('success', 'Bus ajouté avec succès');
    }

    public function destroyBus(Bus $bus)
    {
        if ($bus->departures()->where('scheduled_time', '>=', now())->exists()) {
            return back()->with('error', 'Impossible de supprimer ce bus car il a des départs programmés');
        }

        $bus->delete();
        return back()->with('success', 'Bus supprimé avec succès');
    }

    public function updateBusStatus(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'statut' => 'required|in:En service,En maintenance,Hors service',
        ]);

        $bus->update([
            'statut' => $validated['statut'],
        ]);

        return back()->with('success', 'Statut du bus mis à jour avec succès');
    }

    public function manageReservations()
    {
        $reservations = Reservation::with(['departure'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('dashboard.reservations', compact('reservations'));
    }

    public function updateReservation(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'statut' => 'required|in:Confirmé,En attente,Annulé'
        ]);

        $reservation->update($validated);

        // Implement notification logic here

        return redirect()->back()->with('success', 'Réservation mise à jour avec succès!');
    }

    public function manageDepartures()
    {
        $departures = Departure::orderBy('scheduled_time', 'asc')->paginate(10);
        return view('dashboard.departures', compact('departures'));
    }

    public function manageAdvertisements()
    {
        $advertisements = Advertisement::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.advertisements', compact('advertisements'));
    }

    public function manageAnnouncements()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.announcements', compact('announcements'));
    }

    public function settings()
    {
        return view('dashboard.settings');
    }

    public function updateSiteSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'phone_number' => 'required|string|max:20',
        ]);

        // Update the configuration
        Config::set('app.name', $validated['site_name']);
        Config::set('mail.from.address', $validated['contact_email']);
        Config::set('company.phone', $validated['phone_number']);

        return back()->with('success', 'Paramètres du site mis à jour avec succès');
    }

    public function updateNotificationSettings(Request $request)
    {
        $validated = $request->validate([
            'notify_new_reservation' => 'boolean',
            'notify_departure_status' => 'boolean',
            'notify_low_seats' => 'boolean',
        ]);

        // Update the configuration
        Config::set('notifications.new_reservation', $validated['notify_new_reservation'] ?? false);
        Config::set('notifications.departure_status', $validated['notify_departure_status'] ?? false);
        Config::set('notifications.low_seats', $validated['notify_low_seats'] ?? false);

        return back()->with('success', 'Paramètres de notification mis à jour avec succès');
    }

    public function updateMaintenanceSettings(Request $request)
    {
        $validated = $request->validate([
            'maintenance_interval' => 'required|integer|min:1',
            'maintenance_threshold' => 'required|integer|min:1',
        ]);

        // Update the configuration
        Config::set('maintenance.interval', $validated['maintenance_interval']);
        Config::set('maintenance.threshold', $validated['maintenance_threshold']);

        return back()->with('success', 'Paramètres de maintenance mis à jour avec succès');
    }

    public function updateBookingSettings(Request $request)
    {
        $validated = $request->validate([
            'booking_time_limit' => 'required|integer|min:1',
            'max_seats_per_booking' => 'required|integer|min:1',
            'allow_overbooking' => 'boolean',
        ]);

        // Update the configuration
        Config::set('booking.time_limit', $validated['booking_time_limit']);
        Config::set('booking.max_seats', $validated['max_seats_per_booking']);
        Config::set('booking.allow_overbooking', $validated['allow_overbooking'] ?? false);

        return back()->with('success', 'Paramètres de réservation mis à jour avec succès');
    }

    public function statistics()
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Statistiques mensuelles
        $monthlyStats = [
            'current' => [
                'reservations' => Reservation::whereMonth('created_at', $currentMonth->month)
                    ->whereYear('created_at', $currentMonth->year)
                    ->count(),
                'revenue' => Reservation::whereMonth('created_at', $currentMonth->month)
                    ->whereYear('created_at', $currentMonth->year)
                    ->where('statut', 'Confirmé')
                    ->sum('prix_total'),
                'departures' => Departure::whereMonth('scheduled_time', $currentMonth->month)
                    ->whereYear('scheduled_time', $currentMonth->year)
                    ->count(),
                'month_name' => $currentMonth->format('F')
            ],
            'last' => [
                'reservations' => Reservation::whereMonth('created_at', $lastMonth->month)
                    ->whereYear('created_at', $lastMonth->year)
                    ->count(),
                'revenue' => Reservation::whereMonth('created_at', $lastMonth->month)
                    ->whereYear('created_at', $lastMonth->year)
                    ->where('statut', 'Confirmé')
                    ->sum('prix_total'),
                'departures' => Departure::whereMonth('scheduled_time', $lastMonth->month)
                    ->whereYear('scheduled_time', $lastMonth->year)
                    ->count(),
                'month_name' => $lastMonth->format('F')
            ]
        ];

        // Top 5 des destinations
        $topDestinations = Departure::select('route', DB::raw('count(*) as total'))
            ->groupBy('route')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Statistiques des réservations par statut
        $reservationsByStatus = Reservation::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->get();

        return view('dashboard.statistics', compact(
            'monthlyStats',
            'topDestinations',
            'reservationsByStatus'
        ));
    }
}
