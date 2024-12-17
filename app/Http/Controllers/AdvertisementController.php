<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::orderBy('display_order')->paginate(10);
        return view('dashboard.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        return view('dashboard.advertisements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_type' => 'required|in:upload,youtube,drive',
            'video' => 'required_if:video_type,upload|file|mimetypes:video/mp4,video/quicktime|max:51200',
            'video_link' => 'required_if:video_type,youtube,drive|url',
            'is_active' => 'nullable|in:1,true,false,0',
            'display_order' => 'integer|min:0',
        ]);

        $data = [
            'title' => $validated['title'],
            'video_type' => $validated['video_type'],
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->video_type === 'upload' && $request->hasFile('video')) {
            $path = $request->file('video')->store('advertisements', 'public');
            $data['video_path'] = $path;
        } else {
            $data['video_path'] = $validated['video_link'];
        }

        Advertisement::create($data);

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', 'Publicité créée avec succès.');
    }

    public function edit(Advertisement $advertisement)
    {
        return view('dashboard.advertisements.edit', compact('advertisement'));
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_type' => 'required|in:upload,youtube,drive',
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:51200',
            'video_link' => 'required_if:video_type,youtube,drive|url',
            'is_active' => 'nullable|in:1,true,false,0',
            'display_order' => 'integer|min:0',
        ]);

        $data = [
            'title' => $validated['title'],
            'video_type' => $validated['video_type'],
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->video_type === 'upload' && $request->hasFile('video')) {
            if ($advertisement->video_path && Storage::disk('public')->exists($advertisement->video_path)) {
                Storage::disk('public')->delete($advertisement->video_path);
            }
            $path = $request->file('video')->store('advertisements', 'public');
            $data['video_path'] = $path;
        } elseif (in_array($request->video_type, ['youtube', 'drive'])) {
            $data['video_path'] = $validated['video_link'];
        }

        $advertisement->update($data);

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', 'Publicité mise à jour avec succès.');
    }

    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->video_path && $advertisement->video_type === 'upload' && 
            Storage::disk('public')->exists($advertisement->video_path)) {
            Storage::disk('public')->delete($advertisement->video_path);
        }
        
        $advertisement->delete();

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', 'Publicité supprimée avec succès.');
    }
}
