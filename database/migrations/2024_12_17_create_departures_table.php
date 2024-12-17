<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->string('route');
            $table->time('scheduled_time');
            $table->time('actual_time')->nullable();
            $table->enum('status', ['on_time', 'delayed', 'cancelled'])->default('on_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departures');
    }
};
