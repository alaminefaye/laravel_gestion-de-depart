<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Departure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmation;

class ReservationController extends Controller
{
    public function __construct()
    {
        \Log::info('User Permissions:', [
            'user' => auth()->user() ? auth()->user()->toArray() : null,
            'permissions' => auth()->user() ? auth()->user()->getAllPermissions()->pluck('name') : [],
        ]);
    }

    public function index(Request $request)
    {
        $query = Reservation::with(['departure']);

        // Recherche par référence ou nom
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_client', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('statut', $request->status);
        }

        // Filtre par date
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        $reservations = $query->latest()->paginate(10);
        
        return view('dashboard.reservations.index', [
            'reservations' => $reservations,
            'statuts' => ['en_attente', 'confirmé', 'annulé']
        ]);
    }

    public function create()
    {
        $departures = Departure::where('scheduled_time', '>=', now())
            ->where('places_disponibles', '>', 0)
            ->orderBy('scheduled_time')
            ->get();
            
        return view('dashboard.reservations.create', [
            'departures' => $departures,
            'statuts' => ['en_attente', 'confirmé', 'annulé']
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'departure_id' => 'required|exists:departures,id',
            'nom_client' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nombre_places' => 'required|integer|min:1',
            'prix_total' => 'required|numeric|min:0',
            'statut' => 'required|in:en_attente,confirmé,annulé',
            'siege_numeros' => 'required|json'
        ]);

        $departure = Departure::findOrFail($validated['departure_id']);
        $siegeNumeros = json_decode($validated['siege_numeros']);
        
        // Vérifier que le nombre de sièges sélectionnés correspond au nombre de places
        if (count($siegeNumeros) != $validated['nombre_places']) {
            return back()
                ->withInput()
                ->withErrors(['siege_numeros' => 'Veuillez sélectionner le bon nombre de sièges.']);
        }
        
        // Vérifier que les sièges sont disponibles
        $occupiedSeats = $departure->reservations()
            ->whereJsonLength('siege_numeros', '>', 0)
            ->get()
            ->pluck('siege_numeros')
            ->flatten()
            ->unique();
            
        $unavailableSeats = array_intersect($siegeNumeros, $occupiedSeats->toArray());
        if (!empty($unavailableSeats)) {
            return back()
                ->withInput()
                ->withErrors(['siege_numeros' => 'Certains sièges sélectionnés ne sont plus disponibles.']);
        }

        // Vérifier la disponibilité des places
        if ($departure->places_disponibles < $validated['nombre_places']) {
            return back()
                ->withInput()
                ->withErrors(['nombre_places' => 'Il n\'y a pas assez de places disponibles pour ce départ.']);
        }

        DB::beginTransaction();
        try {
            // Créer la réservation
            $reservation = Reservation::create($validated);

            // Mettre à jour les places disponibles
            $departure->places_disponibles -= $validated['nombre_places'];
            $departure->save();

            // Envoyer l'email de confirmation
            if ($validated['statut'] === 'confirmé') {
                Mail::to($validated['email'])->send(new ReservationConfirmation($reservation));
            }

            DB::commit();

            return redirect()
                ->route('dashboard.reservations.show', $reservation)
                ->with('success', 'Réservation créée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la réservation.');
        }
    }

    public function show(Reservation $reservation)
    {
        return view('dashboard.reservations.show', [
            'reservation' => $reservation,
            'statuts' => ['en_attente', 'confirmé', 'annulé']
        ]);
    }

    public function edit(Reservation $reservation)
    {
        $departures = Departure::where('scheduled_time', '>=', now())
            ->orderBy('scheduled_time')
            ->get();
            
        return view('dashboard.reservations.edit', [
            'reservation' => $reservation,
            'departures' => $departures,
            'statuts' => ['en_attente', 'confirmé', 'annulé']
        ]);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'departure_id' => 'required|exists:departures,id',
            'nom_client' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nombre_places' => 'required|integer|min:1',
            'prix_total' => 'required|numeric|min:0',
            'statut' => 'required|in:en_attente,confirmé,annulé',
            'siege_numeros' => 'required|json'
        ]);

        DB::beginTransaction();
        try {
            // Si le départ a changé, mettre à jour les places disponibles
            if ($reservation->departure_id != $validated['departure_id']) {
                // Rendre les places à l'ancien départ
                $oldDeparture = $reservation->departure;
                $oldDeparture->places_disponibles += $reservation->nombre_places;
                $oldDeparture->save();

                // Vérifier les places du nouveau départ
                $newDeparture = Departure::findOrFail($validated['departure_id']);
                if ($newDeparture->places_disponibles < $validated['nombre_places']) {
                    throw new \Exception('Pas assez de places disponibles sur le nouveau départ.');
                }

                // Réserver les places sur le nouveau départ
                $newDeparture->places_disponibles -= $validated['nombre_places'];
                $newDeparture->save();
            }
            // Si seul le nombre de places a changé
            elseif ($reservation->nombre_places != $validated['nombre_places']) {
                $departure = $reservation->departure;
                $placeDiff = $validated['nombre_places'] - $reservation->nombre_places;
                
                if ($placeDiff > 0 && $departure->places_disponibles < $placeDiff) {
                    throw new \Exception('Pas assez de places disponibles.');
                }
                
                $departure->places_disponibles -= $placeDiff;
                $departure->save();
            }

            $reservation->update($validated);

            DB::commit();

            return redirect()
                ->route('dashboard.reservations.show', $reservation)
                ->with('success', 'Réservation mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la réservation: ' . $e->getMessage());
        }
    }

    public function destroy(Reservation $reservation)
    {
        DB::beginTransaction();
        try {
            // Rendre les places au départ
            $departure = $reservation->departure;
            $departure->places_disponibles += $reservation->nombre_places;
            $departure->save();

            $reservation->delete();

            DB::commit();

            return redirect()
                ->route('dashboard.reservations.index')
                ->with('success', 'Réservation supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la réservation.');
        }
    }

    public function confirm(Reservation $reservation)
    {
        if ($reservation->statut !== 'en_attente') {
            return back()->with('error', 'Cette réservation ne peut pas être confirmée.');
        }

        $reservation->statut = 'confirmé';
        $reservation->save();

        // Envoyer l'email de confirmation
        Mail::to($reservation->email)->send(new ReservationConfirmation($reservation));

        return back()->with('success', 'Réservation confirmée avec succès.');
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->statut === 'annulé') {
            return back()->with('error', 'Cette réservation est déjà annulée.');
        }

        DB::beginTransaction();
        try {
            // Rendre les places au départ
            $departure = $reservation->departure;
            $departure->places_disponibles += $reservation->nombre_places;
            $departure->save();

            $reservation->statut = 'annulé';
            $reservation->save();

            DB::commit();

            return back()->with('success', 'Réservation annulée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Une erreur est survenue lors de l\'annulation de la réservation.');
        }
    }
}
