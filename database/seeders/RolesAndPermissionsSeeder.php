<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for users
        $userPermissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        // Create permissions for roles
        $rolePermissions = [
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
        ];

        // Create permissions for departures
        $departurePermissions = [
            'view departures',
            'create departures',
            'edit departures',
            'delete departures',
            'update departure status',
        ];

        // Create permissions for buses
        $busPermissions = [
            'view buses',
            'create buses',
            'edit buses',
            'delete buses',
        ];

        // Create permissions for advertisements
        $advertisementPermissions = [
            'view advertisements',
            'create advertisements',
            'edit advertisements',
            'delete advertisements',
        ];

        // Create permissions for announcements
        $announcementPermissions = [
            'view announcements',
            'create announcements',
            'edit announcements',
            'delete announcements',
            'toggle announcements',
        ];

        // Create permissions for reservations
        $reservationPermissions = [
            'view reservations',
            'create reservations',
            'edit reservations',
            'delete reservations',
            'confirm reservations',
            'cancel reservations',
        ];

        // Combine all permissions
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $departurePermissions,
            $busPermissions,
            $advertisementPermissions,
            $announcementPermissions,
            $reservationPermissions
        );

        // Create or update permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'Super Admin' => $allPermissions,
            'Admin' => array_merge(
                $departurePermissions,
                $busPermissions,
                $advertisementPermissions,
                $announcementPermissions,
                $reservationPermissions
            ),
            'Manager' => array_merge(
                ['view users'],
                $departurePermissions,
                $busPermissions,
                $reservationPermissions
            ),
            'Agent' => array_merge(
                ['view departures', 'view buses'],
                $reservationPermissions
            ),
            'User' => [
                'view departures',
                'view buses',
                'view advertisements',
                'view announcements',
                'create reservations',
                'view reservations',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }
    }
}
