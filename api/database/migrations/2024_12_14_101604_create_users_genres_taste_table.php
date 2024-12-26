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
        Schema::create('users_genres_taste', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('genre_id');
            $table->timestamps();

            $table->foreign('user_id', 'fk_user_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('genre_id', 'fk_user_genre')
                ->references('id')
                ->on('genres')
                ->onDelete('cascade');

            $table->unique(['user_id', 'genre_id'], 'unique_user_genre_taste');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_genres_taste');
    }
};
