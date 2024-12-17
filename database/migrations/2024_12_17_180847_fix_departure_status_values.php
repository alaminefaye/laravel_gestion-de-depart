<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE departures MODIFY COLUMN status ENUM('A l\\'heure', 'En retard', 'Annule') NOT NULL DEFAULT 'A l\\'heure'");
        
        // Update existing statuses
        DB::table('departures')
            ->where('status', 'Annulé')
            ->update(['status' => 'Annule']);
    }

    public function down()
    {
        DB::statement("ALTER TABLE departures MODIFY COLUMN status ENUM('A l\\'heure', 'En retard', 'Annulé') NOT NULL DEFAULT 'A l\\'heure'");
        
        // Update existing statuses back
        DB::table('departures')
            ->where('status', 'Annule')
            ->update(['status' => 'Annulé']);
    }
};
