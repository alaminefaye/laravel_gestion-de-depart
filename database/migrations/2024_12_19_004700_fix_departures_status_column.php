<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('departures', function (Blueprint $table) {
            DB::statement("ALTER TABLE departures MODIFY status VARCHAR(255) NOT NULL DEFAULT 'A l''heure'");
        });

        // Update existing statuses
        DB::table('departures')
            ->where('status', 'On time')
            ->update(['status' => 'A l\'heure']);
            
        DB::table('departures')
            ->where('status', 'Delayed')
            ->update(['status' => 'En retard']);
            
        DB::table('departures')
            ->where('status', 'Cancelled')
            ->update(['status' => 'Annulé']);
    }

    public function down()
    {
        Schema::table('departures', function (Blueprint $table) {
            DB::statement("ALTER TABLE departures MODIFY status VARCHAR(255) NOT NULL DEFAULT 'On time'");
        });

        // Revert status values
        DB::table('departures')
            ->where('status', 'A l\'heure')
            ->update(['status' => 'On time']);
            
        DB::table('departures')
            ->where('status', 'En retard')
            ->update(['status' => 'Delayed']);
            
        DB::table('departures')
            ->where('status', 'Annulé')
            ->update(['status' => 'Cancelled']);
    }
};
