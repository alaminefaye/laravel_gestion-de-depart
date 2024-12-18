<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::latest()->paginate(10);
        return view('dashboard.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('dashboard.buses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:255|unique:buses',
            'modele' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'statut' => 'required|in:En service,En maintenance,Hors service'
        ]);

        // Ensure proper status value
        if (!in_array($validated['statut'], ['En service', 'En maintenance', 'Hors service'])) {
            $validated['statut'] = 'En service'; // Default value if invalid
        }

        Bus::create($validated);

        return redirect()->route('dashboard.buses.index')
            ->with('success', 'Bus ajouté avec succès');
    }

    public function show(Bus $bus)
    {
        return view('dashboard.buses.show', compact('bus'));
    }

    public function edit(Bus $bus)
    {
        return view('dashboard.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:255|unique:buses,numero,' . $bus->id,
            'modele' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'statut' => 'required|in:Actif,En maintenance,Hors service'
        ]);

        $bus->update($validated);

        return redirect()->route('dashboard.buses.index')
            ->with('success', 'Bus mis à jour avec succès');
    }

    public function destroy(Bus $bus)
    {
        try {
            $bus->delete();
            return redirect()->route('dashboard.buses.index')
                ->with('success', 'Bus supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.buses.index')
                ->with('error', 'Impossible de supprimer ce bus car il est lié à des départs');
        }
    }
}
