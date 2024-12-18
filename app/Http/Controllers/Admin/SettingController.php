<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSettings();
        return view('dashboard.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = SiteSetting::getSettings();
        
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_phone_2' => 'nullable|string|max:255',
            'footer_text' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($settings->logo_path) {
                Storage::delete('public/' . $settings->logo_path);
            }

            // Stocker le nouveau logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $settings->logo_path = $logoPath;
        }

        $settings->site_name = $validated['site_name'];
        $settings->slogan = $validated['slogan'];
        $settings->contact_email = $validated['contact_email'];
        $settings->contact_phone = $validated['contact_phone'];
        $settings->contact_phone_2 = $validated['contact_phone_2'];
        $settings->footer_text = $validated['footer_text'];
        $settings->save();

        return redirect()->route('dashboard.settings')->with('success', 'Paramètres mis à jour avec succès');
    }
}
