<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('you_tube_videos', function (Blueprint $table) {
            $table->unique('youtube_id', 'youtube_videos_youtube_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('you_tube_videos', function (Blueprint $table) {
            $table->dropUnique('youtube_videos_youtube_id_unique');
        });
    }
};
