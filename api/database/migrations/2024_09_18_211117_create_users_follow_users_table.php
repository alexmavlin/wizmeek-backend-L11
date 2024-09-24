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
        // Create the pivot table for the many-to-many relationship between users (followers and followed)
        Schema::create('users_follow_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('follower_user_id'); // Follower user ID
            $table->unsignedBigInteger('followed_user_id'); // Followed user ID
            $table->timestamps();

            // Foreign keys
            $table->foreign('follower_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('followed_user_id')->references('id')->on('users')->cascadeOnDelete();

            // Ensure that each pair of follower and followed is unique
            $table->unique(['follower_user_id', 'followed_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the pivot table
        Schema::dropIfExists('users_follow_users');
    }
};
