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
            $table->text('spotify_link', 1000)->nullable()->after('original_link');
            $table->text('apple_music_link', 1000)->nullable()->after('spotify_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('you_tube_videos', function (Blueprint $table) {
            $table->dropColumn('spotify_link');
            $table->dropColumn('apple_music_link');
        });
    }
};
