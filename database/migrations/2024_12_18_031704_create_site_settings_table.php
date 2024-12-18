<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();
            $table->string('site_name')->default('ART Luxury Bus');
            $table->string('slogan')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_phone_2')->nullable();
            $table->text('footer_text')->nullable();
            $table->timestamps();
        });

        // Insérer les paramètres par défaut
        DB::table('site_settings')->insert([
            'site_name' => 'ART Luxury Bus',
            'slogan' => '',
            'contact_phone' => '',
            'contact_phone_2' => '',
            'contact_email' => '',
            'footer_text' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
