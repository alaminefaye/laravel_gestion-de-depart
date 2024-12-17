<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Ajouter une nouvelle colonne temporaire
        Schema::table('departures', function (Blueprint $table) {
            $table->string('new_status', 20)->default('on_time')->after('status');
        });

        // 2. Copier les données avec les bonnes valeurs
        DB::table('departures')
            ->where('status', 'like', '%delayed%')
            ->update(['new_status' => 'delayed']);
        
        DB::table('departures')
            ->where('status', 'like', '%cancelled%')
            ->update(['new_status' => 'cancelled']);

        // 3. Supprimer l'ancienne colonne
        Schema::table('departures', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // 4. Renommer la nouvelle colonne
        Schema::table('departures', function (Blueprint $table) {
            $table->renameColumn('new_status', 'status');
        });

        // 5. Modifier le type de la colonne en ENUM
        DB::statement("ALTER TABLE departures MODIFY COLUMN status ENUM('on_time', 'delayed', 'cancelled') NOT NULL DEFAULT 'on_time'");
    }

    public function down()
    {
        Schema::table('departures', function (Blueprint $table) {
            $table->string('status', 20)->default('on_time')->change();
        });
    }
};
