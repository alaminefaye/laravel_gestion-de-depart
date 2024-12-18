<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('dashboard.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('dashboard.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'permissions' => ['required', 'array']
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('dashboard.roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('dashboard.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'permissions' => ['required', 'array']
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('dashboard.roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Ce rôle ne peut pas être supprimé car il est attribué à des utilisateurs.');
        }

        $role->delete();

        return redirect()->route('dashboard.roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }
}
