<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::orderBy('display_order')->get();
        return view('admin.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        return view('admin.advertisements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_type' => 'required|in:upload,youtube,drive',
            'video' => 'required_if:video_type,upload|file|mimetypes:video/mp4,video/webm|max:51200', // 50MB
            'video_link' => 'required_if:video_type,youtube,drive|url',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = [
            'title' => $request->title,
            'video_type' => $request->video_type,
            'display_order' => $request->display_order,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->video_type === 'upload' && $request->hasFile('video')) {
            $path = $request->file('video')->store('advertisements', 'public');
            $data['video_path'] = $path;
        } else {
            $data['video_link'] = $request->video_link;
        }

        Advertisement::create($data);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Publicité ajoutée avec succès.');
    }

    public function edit(Advertisement $advertisement)
    {
        return view('admin.advertisements.edit', compact('advertisement'));
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_type' => 'required|in:upload,youtube,drive',
            'video' => 'nullable|file|mimetypes:video/mp4,video/webm|max:51200', // 50MB
            'video_link' => 'required_if:video_type,youtube,drive|url',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = [
            'title' => $request->title,
            'video_type' => $request->video_type,
            'display_order' => $request->display_order,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->video_type === 'upload') {
            if ($request->hasFile('video')) {
                // Delete old video if exists
                if ($advertisement->video_path) {
                    Storage::disk('public')->delete($advertisement->video_path);
                }
                $path = $request->file('video')->store('advertisements', 'public');
                $data['video_path'] = $path;
            }
            $data['video_link'] = null;
        } else {
            $data['video_link'] = $request->video_link;
            if ($advertisement->video_path) {
                Storage::disk('public')->delete($advertisement->video_path);
                $data['video_path'] = null;
            }
        }

        $advertisement->update($data);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Publicité mise à jour avec succès.');
    }

    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->video_path) {
            Storage::disk('public')->delete($advertisement->video_path);
        }
        
        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Publicité supprimée avec succès.');
    }
}
