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
        Schema::create('artists_countries', function (Blueprint $table) {
            $table->unsignedBigInteger('artist_id')->comment('Artist FK');
            $table->unsignedBigInteger('country_id')->comment('Country FK');

            $table->foreign('artist_id', 'artist_artists_countries_fk')->on('artists')->references('id')->onDelete('cascade');
            $table->foreign('country_id', 'country_artists_countries_fk')->on('countries')->references('id')->onDelete('cascade');

            $table->primary(['artist_id', 'country_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists_countries');
    }
};
