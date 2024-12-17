<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            // Add the new year column
            $table->integer('annee')->after('capacite');
            
            // Drop the maintenance columns
            $table->dropColumn(['derniere_maintenance', 'prochaine_maintenance']);
            
            // Update the status enum values
            DB::statement("ALTER TABLE buses MODIFY COLUMN statut ENUM('Actif', 'En maintenance', 'Hors service') NOT NULL DEFAULT 'Actif'");
            
            // Update existing statuses
            DB::table('buses')
                ->where('statut', 'En service')
                ->update(['statut' => 'Actif']);
        });
    }

    public function down()
    {
        Schema::table('buses', function (Blueprint $table) {
            // Remove the year column
            $table->dropColumn('annee');
            
            // Add back the maintenance columns
            $table->timestamp('derniere_maintenance')->nullable();
            $table->timestamp('prochaine_maintenance')->nullable();
            
            // Revert the status enum values
            DB::statement("ALTER TABLE buses MODIFY COLUMN statut ENUM('En service', 'En maintenance', 'Hors service') NOT NULL DEFAULT 'En service'");
            
            // Update existing statuses back
            DB::table('buses')
                ->where('statut', 'Actif')
                ->update(['statut' => 'En service']);
        });
    }
};
