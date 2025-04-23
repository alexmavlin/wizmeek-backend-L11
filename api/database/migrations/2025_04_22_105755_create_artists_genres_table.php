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
        Schema::create('artists_genres', function (Blueprint $table) {
            $table->unsignedBigInteger('artist_id')->comment('Artist FK');
            $table->unsignedBigInteger('genre_id')->comment('Genre FK');

            $table->foreign('artist_id', 'artist_artists_genres_fk')->on('artists')->references('id')->onDelete('cascade');
            $table->foreign('genre_id', 'genre_artists_genres_fk')->on('genres')->references('id')->onDelete('cascade');

            $table->primary(['artist_id', 'genre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists_genres');
    }
};
