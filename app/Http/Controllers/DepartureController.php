<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Departure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DepartureController extends Controller
{
    public function index(Request $request)
    {
        $query = Departure::with(['bus']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('route', 'like', "%{$search}%")
                  ->orWhereHas('bus', function($q) use ($search) {
                      $q->where('numero', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_time', $request->date);
        }

        $departures = $query->latest()->paginate(10);
        
        $stats = [
            'total_departures' => Departure::count(),
            'departures_today' => Departure::whereDate('scheduled_time', Carbon::today())->count(),
            'average_places' => round(Departure::avg('places_disponibles'), 0),
            'delayed_count' => Departure::where('status', Departure::STATUS_DELAYED)->count()
        ];

        $buses = Bus::all();

        return view('dashboard.departures.index', compact('departures', 'stats', 'buses'));
    }

    public function create()
    {
        $buses = Bus::all();
        return view('dashboard.departures.create', compact('buses'));
    }

    public function store(Request $request)
    {
        $bus = Bus::findOrFail($request->bus_id);
        
        $validatedData = $request->validate([
            'route' => 'required|string|max:255',
            'scheduled_time' => 'required|date',
            'delayed_time' => 'nullable|date|after_or_equal:scheduled_time',
            'status' => 'required|integer|in:1,2,3',
            'bus_id' => 'required|exists:buses,id',
            'prix' => 'required|numeric|min:0',
            'places_disponibles' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($bus) {
                    if ($value > $bus->capacite) {
                        $fail('Le nombre de places disponibles ne peut pas dépasser la capacité du bus (' . $bus->capacite . ' places).');
                    }
                },
            ],
        ]);

        try {
            Log::info('Création d\'un départ', [
                'data' => $validatedData,
                'request' => $request->all()
            ]);

            // Si le statut est "En retard" et qu'il n'y a pas de delayed_time, on utilise scheduled_time + 1 heure
            if ($validatedData['status'] == Departure::STATUS_DELAYED) {
                if (empty($validatedData['delayed_time'])) {
                    $scheduledTime = Carbon::parse($validatedData['scheduled_time']);
                    $validatedData['delayed_time'] = $scheduledTime->addHour();
                }
            } else {
                $validatedData['delayed_time'] = null;
            }

            $departure = Departure::create($validatedData);

            Log::info('Départ créé avec succès', [
                'departure_id' => $departure->id,
                'data' => $departure->toArray()
            ]);

            return redirect()->route('dashboard.departures.index')
                ->with('success', 'Départ créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du départ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du départ : ' . $e->getMessage());
        }
    }

    public function edit(Departure $departure)
    {
        $buses = Bus::all();
        return view('dashboard.departures.edit', compact('departure', 'buses'));
    }

    public function update(Request $request, Departure $departure)
    {
        $bus = Bus::findOrFail($request->bus_id);
        
        $validatedData = $request->validate([
            'route' => 'required|string|max:255',
            'scheduled_time' => 'required|date',
            'delayed_time' => 'nullable|date|after_or_equal:scheduled_time',
            'status' => 'required|in:'.Departure::STATUS_ON_TIME.','.Departure::STATUS_DELAYED.','.Departure::STATUS_CANCELLED,
            'bus_id' => 'required|exists:buses,id',
            'prix' => 'required|numeric|min:0',
            'places_disponibles' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($bus) {
                    if ($value > $bus->capacite) {
                        $fail('Le nombre de places disponibles ne peut pas dépasser la capacité du bus (' . $bus->capacite . ' places).');
                    }
                },
            ],
        ]);

        try {
            Log::info('Mise à jour d\'un départ', [
                'departure_id' => $departure->id,
                'old_data' => $departure->toArray(),
                'new_data' => $validatedData,
                'request' => $request->all()
            ]);

            // Si le statut est "En retard" et qu'il n'y a pas de delayed_time, on utilise scheduled_time + 1 heure
            if ($validatedData['status'] == Departure::STATUS_DELAYED) {
                if (empty($validatedData['delayed_time'])) {
                    $scheduledTime = Carbon::parse($validatedData['scheduled_time']);
                    $validatedData['delayed_time'] = $scheduledTime->addHour();
                }
            } else {
                $validatedData['delayed_time'] = null;
            }

            $departure->update($validatedData);

            Log::info('Départ mis à jour avec succès', [
                'departure_id' => $departure->id,
                'data' => $departure->fresh()->toArray()
            ]);

            return redirect()->route('dashboard.departures.index')
                ->with('success', 'Départ mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du départ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du départ : ' . $e->getMessage());
        }
    }

    public function destroy(Departure $departure)
    {
        try {
            $departure->delete();
            return redirect()->route('dashboard.departures.index')
                ->with('success', 'Départ supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du départ.');
        }
    }
}
