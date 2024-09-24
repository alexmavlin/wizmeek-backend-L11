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
        Schema::create('you_tube_videos', function (Blueprint $table) {
            $table->id();
            $table->string('original_link', 250);
            $table->string('youtube_id', 50);
            $table->unsignedBigInteger('content_type_id');
            $table->unsignedBigInteger('artist_id');
            $table->string('title', 150);
            $table->string('thumbnail', 255);
            $table->date('release_date');
            $table->unsignedBigInteger('genre_id');
            $table->unsignedBigInteger('country_id');
            $table->boolean('editors_pick');
            $table->boolean('new');
            $table->boolean('throwback');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('content_type_id')->references('id')->on('content_types');
            $table->foreign('artist_id')->references('id')->on('artists');
            $table->foreign('genre_id')->references('id')->on('genres');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('you_tube_videos');
    }
};
