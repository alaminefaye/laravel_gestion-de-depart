<?php

namespace App\Http\Controllers;

use App\Models\Departure;
use Illuminate\Http\Request;

class DepartureController extends Controller
{
    public function index()
    {
        $departures = Departure::orderBy('scheduled_time')->get();
        return response()->json($departures);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route' => 'required|string',
            'scheduled_time' => 'required|date_format:H:i',
            'status' => 'required|in:on_time,delayed,cancelled'
        ]);

        $departure = Departure::create($validated);
        return response()->json($departure, 201);
    }

    public function update(Request $request, Departure $departure)
    {
        $validated = $request->validate([
            'actual_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:on_time,delayed,cancelled'
        ]);

        $departure->update($validated);
        return response()->json($departure);
    }
}
