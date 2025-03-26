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
        Schema::table('artists', function (Blueprint $table) {
            $table->string('spotify_link', 255)->default("")->comment('Spotify page')->after('full_description');
            $table->string('apple_music_link', 255)->default("")->comment('Apple Music page')->after('spotify_link');
            $table->string('instagram_link', 255)->default("")->comment('Instagram page')->after('apple_music_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('spotify_link');
            $table->dropColumn('apple_music_link');
            $table->dropColumn('instagram_link');
        });
    }
};
