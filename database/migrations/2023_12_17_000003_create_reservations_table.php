<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique();
            $table->string('nom_client');
            $table->string('email');
            $table->string('telephone');
            $table->integer('nombre_places');
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut', ['Confirmé', 'En attente', 'Annulé'])->default('En attente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
