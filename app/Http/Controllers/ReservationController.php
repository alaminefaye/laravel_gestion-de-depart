<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Departure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['departure']);

        // Recherche par référence ou nom
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('nom_client', 'like', "%{$search}%")
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
            'statuts' => ['En attente', 'Confirmé', 'Annulé']
        ]);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('departure');
        return view('dashboard.reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $reservation->load('departure');
        $statuts = ['En attente', 'Confirmé', 'Annulé'];
        return view('dashboard.reservations.edit', compact('reservation', 'statuts'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'statut' => 'required|in:En attente,Confirmé,Annulé',
            'notes_admin' => 'nullable|string|max:1000',
        ]);

        $reservation->update($validated);

        return redirect()
            ->route('dashboard.reservations.index')
            ->with('success', 'Réservation mise à jour avec succès');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()
            ->route('dashboard.reservations.index')
            ->with('success', 'Réservation supprimée avec succès');
    }
}
