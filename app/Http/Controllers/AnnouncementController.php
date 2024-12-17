<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::all();
        return view('dashboard.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('dashboard.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
            'position' => 'required|in:header,footer,sidebar',
            'is_active' => 'boolean'
        ]);

        Announcement::create($validated);

        return redirect()->route('dashboard.announcements.index')
            ->with('success', 'Annonce créée avec succès.');
    }

    public function edit(Announcement $announcement)
    {
        return view('dashboard.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
            'position' => 'required|in:header,footer,sidebar',
            'is_active' => 'boolean'
        ]);

        $announcement->update($validated);

        return redirect()->route('dashboard.announcements.index')
            ->with('success', 'Annonce mise à jour avec succès.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('dashboard.announcements.index')
            ->with('success', 'Annonce supprimée avec succès.');
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->update([
            'is_active' => !$announcement->is_active
        ]);

        return back()->with('success', 'Statut de l\'annonce mis à jour avec succès.');
    }

    // API endpoint for front-end
    public function getActive()
    {
        $announcements = Announcement::where('is_active', true)
            ->orderBy('position')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($announcements);
    }
}
