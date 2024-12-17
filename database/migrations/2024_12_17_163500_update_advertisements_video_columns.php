<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            // Ajouter la nouvelle colonne si elle n'existe pas déjà
            if (!Schema::hasColumn('advertisements', 'video_path')) {
                $table->string('video_path')->nullable()->after('video_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('video_path');
        });
    }
};
