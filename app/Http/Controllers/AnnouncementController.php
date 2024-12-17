<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('end_at')
                    ->orWhere('end_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($announcements);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'type' => 'required|in:info,alert,promotion',
            'is_active' => 'boolean',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after:start_at'
        ]);

        $announcement = Announcement::create($validated);
        return response()->json($announcement, 201);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'message' => 'string',
            'type' => 'in:info,alert,promotion',
            'is_active' => 'boolean',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after:start_at'
        ]);

        $announcement->update($validated);
        return response()->json($announcement);
    }
}
