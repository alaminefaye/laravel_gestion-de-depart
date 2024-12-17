<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // First admin user
        if (!DB::table('users')->where('email', 'admin@admin.co')->exists()) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'admin@admin.co',
                'password' => Hash::make('passer123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Second admin user
        if (!DB::table('users')->where('email', 'admin2@admin.co')->exists()) {
            DB::table('users')->insert([
                'name' => 'Admin 2',
                'email' => 'admin2@admin.co',
                'password' => Hash::make('passer123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('users')
            ->whereIn('email', ['admin@admin.co', 'admin2@admin.co'])
            ->delete();
    }
};
