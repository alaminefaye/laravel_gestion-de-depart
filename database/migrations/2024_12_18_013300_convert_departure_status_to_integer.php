<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Departure;

class ConvertDepartureStatusToInteger extends Migration
{
    public function up()
    {
        Schema::table('departures', function (Blueprint $table) {
            // Créer une nouvelle colonne temporaire
            $table->integer('new_status')->nullable()->after('status');
        });

        // Convertir les valeurs
        DB::table('departures')->get()->each(function ($departure) {
            $newStatus = (int) $departure->status;
            DB::table('departures')
                ->where('id', $departure->id)
                ->update(['new_status' => $newStatus]);
        });

        Schema::table('departures', function (Blueprint $table) {
            // Supprimer l'ancienne colonne
            $table->dropColumn('status');
        });

        Schema::table('departures', function (Blueprint $table) {
            // Renommer la nouvelle colonne
            $table->renameColumn('new_status', 'status');
        });

        Schema::table('departures', function (Blueprint $table) {
            // S'assurer que la colonne n'est pas nullable
            $table->integer('status')->default(1)->change();
        });
    }

    public function down()
    {
        Schema::table('departures', function (Blueprint $table) {
            // Convertir en string si nécessaire de revenir en arrière
            $table->string('status')->default('1')->change();
        });
    }
}
