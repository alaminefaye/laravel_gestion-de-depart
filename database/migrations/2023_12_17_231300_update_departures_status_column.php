<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // D'abord, convertir les statuts existants en nombres
        DB::table('departures')->where('status', 'on_time')->update(['status' => '1']);
        DB::table('departures')->where('status', 'delayed')->update(['status' => '2']);
        DB::table('departures')->where('status', 'cancelled')->update(['status' => '3']);

        // Modifier la colonne status pour utiliser ENUM avec des nombres
        Schema::table('departures', function (Blueprint $table) {
            $table->string('status')->change();
        });

        // Mettre à jour la colonne pour utiliser ENUM avec les nouvelles valeurs
        DB::statement("ALTER TABLE departures MODIFY COLUMN status ENUM('1', '2', '3') DEFAULT '1'");
    }

    public function down()
    {
        // Convertir les nombres en statuts textuels
        DB::table('departures')->where('status', '1')->update(['status' => 'on_time']);
        DB::table('departures')->where('status', '2')->update(['status' => 'delayed']);
        DB::table('departures')->where('status', '3')->update(['status' => 'cancelled']);

        // Remettre la colonne status en ENUM avec les anciennes valeurs
        DB::statement("ALTER TABLE departures MODIFY COLUMN status ENUM('on_time', 'delayed', 'cancelled') DEFAULT 'on_time'");
    }
};
