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
            $table->foreignId('user_id')->constrained();
            $table->foreignId('departure_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique();
            $table->integer('nombre_places');
            $table->decimal('prix_total', 10, 2);
            $table->enum('statut', ['confirmé', 'en_attente', 'annulé'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
