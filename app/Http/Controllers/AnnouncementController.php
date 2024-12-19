<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnnouncementController extends Controller
{
    private function clearAnnouncementCache()
    {
        Cache::forget('active_announcements');
    }

    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
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

        // Générer un titre à partir du contenu
        $validated['title'] = substr($validated['content'], 0, 100);

        Announcement::create($validated);
        
        // Vider le cache après création
        $this->clearAnnouncementCache();

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

        // Mettre à jour le titre si le contenu a changé
        if ($announcement->content !== $validated['content']) {
            $validated['title'] = substr($validated['content'], 0, 100);
        }

        // Convertir explicitement is_active en booléen
        $validated['is_active'] = $request->boolean('is_active');

        $announcement->update($validated);
        
        // Vider le cache après mise à jour
        $this->clearAnnouncementCache();

        return redirect()->route('dashboard.announcements.index')
            ->with('success', 'Annonce mise à jour avec succès.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        
        // Vider le cache après suppression
        $this->clearAnnouncementCache();

        return redirect()->route('dashboard.announcements.index')
            ->with('success', 'Annonce supprimée avec succès.');
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->update([
            'is_active' => !$announcement->is_active
        ]);
        
        // Vider le cache après changement de statut
        $this->clearAnnouncementCache();

        return back()->with('success', 'Statut de l\'annonce mis à jour avec succès.');
    }

    // API endpoint for front-end
    public function getActive()
    {
        return response()->json(
            Cache::remember('active_announcements', 300, function () {
                return Announcement::where('is_active', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            })
        );
    }
}
