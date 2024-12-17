<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // D'abord, mettre à jour toutes les valeurs existantes pour s'assurer qu'elles sont valides
        DB::table('departures')->update(['status' => 'on_time']);

        // Modifier la colonne pour utiliser ENUM
        DB::statement("ALTER TABLE departures MODIFY COLUMN status ENUM('on_time', 'delayed', 'cancelled') NOT NULL DEFAULT 'on_time'");
    }

    public function down()
    {
        // Reconvertir en VARCHAR
        DB::statement("ALTER TABLE departures MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'on_time'");
    }
};
