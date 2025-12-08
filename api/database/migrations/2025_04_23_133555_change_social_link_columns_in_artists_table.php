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
            $table->string('spotify_link', 250)->default('')->change();
            $table->string('apple_music_link', 250)->default('')->change();
            $table->string('instagram_link', 250)->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('spotify_link', 250)->nullable()->default(null)->change();
            $table->string('apple_music_link', 250)->nullable()->default(null)->change();
            $table->string('instagram_link', 250)->nullable()->default(null)->change();
        });
    }
};
