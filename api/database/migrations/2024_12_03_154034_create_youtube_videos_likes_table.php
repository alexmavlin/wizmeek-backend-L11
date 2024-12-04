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
        Schema::create('youtube_videos_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('video_id')->index();
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('user_id', 'fk_youtube_videos_likes_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Optional, to handle cascade deletes.

            $table->foreign('video_id', 'fk_youtube_videos_likes_video_id')
                ->references('id')
                ->on('you_tube_videos')
                ->onDelete('cascade'); // Optional, to handle cascade deletes.

            // Unique Constraint
            $table->unique(['user_id', 'video_id'], 'unique_user_video_like');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_videos_likes');
    }
};
