<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->string('route');
            $table->timestamp('scheduled_time');
            $table->timestamp('actual_time')->nullable();
            $table->timestamp('delayed_time')->nullable();
            $table->enum('status', ['on_time', 'delayed', 'cancelled'])->default('on_time');
            $table->decimal('prix', 10, 2);
            $table->integer('places_disponibles');
            $table->decimal('taux_occupation', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departures');
    }
};
