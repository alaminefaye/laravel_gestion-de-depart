<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Departure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepartureController extends Controller
{
    public function index(Request $request)
    {
        $query = Departure::with('bus');

        // Search by route
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('route', 'like', "%{$search}%");
        }

        // Filter by date range
        if ($request->filled('date_start')) {
            $query->whereDate('scheduled_time', '>=', Carbon::parse($request->date_start)->startOfDay());
        }
        if ($request->filled('date_end')) {
            $query->whereDate('scheduled_time', '<=', Carbon::parse($request->date_end)->endOfDay());
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by bus
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        // Filter by occupation rate
        if ($request->filled('places_min')) {
            $query->where('places_disponibles', '>=', $request->places_min);
        }
        if ($request->filled('places_max')) {
            $query->where('places_disponibles', '<=', $request->places_max);
        }

        // Sort
        $sortField = $request->input('sort', 'scheduled_time');
        $sortDirection = $request->input('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $departures = $query->paginate(10)->withQueryString();

        $buses = Bus::all();
        $stats = [
            'total_departures' => Departure::count(),
            'departures_today' => Departure::whereDate('scheduled_time', Carbon::today())->count(),
            'average_places_disponibles' => round(Departure::avg('places_disponibles'), 1),
            'delayed_count' => Departure::where('status', 'En retard')->count()
        ];

        return view('dashboard.departures.index', compact('departures', 'buses', 'stats'));
    }

    public function create()
    {
        $buses = Bus::all();
        return view('dashboard.departures.create', compact('buses'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'route' => 'required|string|max:255',
            'scheduled_time' => 'required',
            'delayed_time' => 'nullable',
            'status' => 'required|string',
            'bus_id' => 'required|exists:buses,id',
            'prix' => 'required|numeric',
            'places_disponibles' => 'required|integer',
        ]);

        try {
            // Convertir les temps en objets Carbon
            if (!empty($validatedData['scheduled_time'])) {
                $validatedData['scheduled_time'] = Carbon::parse($validatedData['scheduled_time']);
            }
            
            if (!empty($validatedData['delayed_time'])) {
                $validatedData['delayed_time'] = Carbon::parse($validatedData['delayed_time']);
            }

            $departure = Departure::create($validatedData);
            
            return redirect()->route('dashboard.departures.index')
                ->with('success', 'Départ créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du départ : ' . $e->getMessage());
        }
    }

    public function edit(Departure $departure)
    {
        try {
            $buses = Bus::all();
            return view('dashboard.departures.edit', compact('departure', 'buses'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard.departures.index')
                ->with('error', 'Erreur lors du chargement du départ : ' . $e->getMessage());
        }
    }

    public function update(Request $request, Departure $departure)
    {
        $validatedData = $request->validate([
            'route' => 'required|string|max:255',
            'scheduled_time' => 'required',
            'delayed_time' => 'nullable',
            'status' => 'required|string',
            'bus_id' => 'required|exists:buses,id',
            'prix' => 'required|numeric',
            'places_disponibles' => 'required|integer',
        ]);

        try {
            // Convertir les temps en objets Carbon
            if (!empty($validatedData['scheduled_time'])) {
                $validatedData['scheduled_time'] = Carbon::parse($validatedData['scheduled_time']);
            }
            
            if (!empty($validatedData['delayed_time'])) {
                $validatedData['delayed_time'] = Carbon::parse($validatedData['delayed_time']);
            }

            $departure->update($validatedData);
            
            return redirect()->route('dashboard.departures.index')
                ->with('success', 'Départ mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du départ : ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Departure $departure)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:1,2,3',
                'new_time' => 'nullable|date_format:H:i'
            ]);

            if ($validated['status'] === '2' && $request->filled('new_time')) {
                $departure->delayed_time = Carbon::parse($request->new_time);
            } else {
                $departure->delayed_time = null;
            }

            $departure->status = $validated['status'];
            $departure->save();

            return response()->json(['message' => 'Status mis à jour avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Departure $departure)
    {
        try {
            $departure->delete();
            return redirect()->route('dashboard.departures.index')
                ->with('success', 'Départ supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du départ : ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $departure = Departure::with('bus')->findOrFail($id);
            return view('dashboard.departures.show', compact('departure'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard.departures.index')
                ->with('error', 'Départ non trouvé : ' . $e->getMessage());
        }
    }
}
