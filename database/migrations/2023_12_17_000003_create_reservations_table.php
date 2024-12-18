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
            $table->integer('nombre_places');
            $table->decimal('prix_total', 10, 2);
            $table->json('siege_numeros')->nullable();
            $table->enum('statut', ['en_attente', 'confirmé', 'annulé'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
