<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeparturesTable extends Migration
{
    public function up()
    {
        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained();
            $table->string('route');
            $table->dateTime('scheduled_time');
            $table->dateTime('delayed_time')->nullable();
            $table->integer('status')->default(1);
            $table->decimal('prix', 10, 2);
            $table->integer('places_disponibles');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departures');
    }
}
