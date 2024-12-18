<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
            if ($settings->logo_path) {
                Storage::delete('public/' . $settings->logo_path);
            }
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

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password',
            'new_password' => ['nullable', 'required_with:current_password', Password::defaults()],
            'password_confirmation' => 'required_with:new_password|same:new_password'
        ]);

        // Vérifier le mot de passe actuel si un nouveau mot de passe est fourni
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])
                    ->withInput();
            }
        }

        // Mettre à jour l'email
        $user->email = $validated['email'];

        // Mettre à jour le mot de passe si fourni
        if ($request->filled('new_password')) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()
            ->route('dashboard.settings')
            ->with('success', 'Profil mis à jour avec succès');
    }
}
