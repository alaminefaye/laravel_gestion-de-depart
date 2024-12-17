<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $ads = Advertisement::where('is_active', true)
            ->orderBy('display_order')
            ->get();
        return response()->json($ads);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'video_path' => 'required|string',
            'is_active' => 'boolean',
            'display_order' => 'integer'
        ]);

        $ad = Advertisement::create($validated);
        return response()->json($ad, 201);
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'title' => 'string',
            'video_path' => 'string',
            'is_active' => 'boolean',
            'display_order' => 'integer'
        ]);

        $advertisement->update($validated);
        return response()->json($advertisement);
    }
}
