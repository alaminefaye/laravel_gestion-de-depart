<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role permissions
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Bus permissions
            'view buses',
            'create buses',
            'edit buses',
            'delete buses',
            
            // Departure permissions
            'view departures',
            'create departures',
            'edit departures',
            'delete departures',
            
            // Announcement permissions
            'view announcements',
            'create announcements',
            'edit announcements',
            'delete announcements',
            
            // Advertisement permissions
            'view advertisements',
            'create advertisements',
            'edit advertisements',
            'delete advertisements',
            
            // Settings permissions
            'view settings',
            'edit settings'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo([
            'view users', 'create users', 'edit users',
            'view roles',
            'view buses', 'create buses', 'edit buses', 'delete buses',
            'view departures', 'create departures', 'edit departures', 'delete departures',
            'view announcements', 'create announcements', 'edit announcements', 'delete announcements',
            'view advertisements', 'create advertisements', 'edit advertisements', 'delete advertisements',
            'view settings', 'edit settings'
        ]);

        $role = Role::create(['name' => 'manager']);
        $role->givePermissionTo([
            'view buses', 'edit buses',
            'view departures', 'create departures', 'edit departures',
            'view announcements', 'create announcements', 'edit announcements',
            'view advertisements', 'create advertisements', 'edit advertisements',
            'view settings'
        ]);

        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo([
            'view buses',
            'view departures',
            'view announcements', 'create announcements', 'edit announcements',
            'view advertisements', 'create advertisements', 'edit advertisements'
        ]);
    }
}
