<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('dashboard.profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password',
            'new_password' => ['nullable', 'required_with:current_password', Password::defaults()],
            'password_confirmation' => 'required_with:new_password|same:new_password'
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])
                    ->withInput();
            }
        }

        $user->email = $validated['email'];

        if ($request->filled('new_password')) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()
            ->route('dashboard.profile.edit')
            ->with('success', 'Profil mis à jour avec succès');
    }
}
