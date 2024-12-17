<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisements', 'video_link')) {
                $table->string('video_link')->nullable();
            }
            if (!Schema::hasColumn('advertisements', 'video_type')) {
                $table->string('video_type')->default('upload');
            }
            if (!Schema::hasColumn('advertisements', 'display_order')) {
                $table->integer('display_order')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            if (Schema::hasColumn('advertisements', 'video_link')) {
                $table->dropColumn('video_link');
            }
            if (Schema::hasColumn('advertisements', 'video_type')) {
                $table->dropColumn('video_type');
            }
            if (Schema::hasColumn('advertisements', 'display_order')) {
                $table->dropColumn('display_order');
            }
        });
    }
};
