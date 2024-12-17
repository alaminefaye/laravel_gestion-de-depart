<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE departures MODIFY COLUMN status ENUM('A l\\'heure', 'En retard', 'Annulé') NOT NULL DEFAULT 'A l\\'heure'");
        
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
        DB::statement("ALTER TABLE departures MODIFY COLUMN status ENUM('On time', 'Delayed', 'Cancelled') NOT NULL DEFAULT 'On time'");
        
        // Update existing statuses back
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
