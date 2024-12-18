<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('modele');
            $table->integer('capacite');
            $table->integer('annee');
            $table->enum('statut', ['En service', 'En maintenance', 'Hors service']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buses');
    }
};
