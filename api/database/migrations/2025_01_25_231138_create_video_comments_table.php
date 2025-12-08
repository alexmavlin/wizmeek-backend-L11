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
        Schema::create('video_comments', function (Blueprint $table) {
            $table->id()->comment('Record ID');
            $table->text('content', 1500)->comment('Video commentary body');
            $table->unsignedBigInteger('user_id')->comment('User ID');
            $table->unsignedBigInteger('youtube_video_id')->comment('Video ID');
            $table->timestamps();

            $table->foreign('user_id', 'comment_user_fk')->references('id')->on('users');
            $table->foreign('youtube_video_id', 'comment_video_fk')->references('id')->on('you_tube_videos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_comments');
    }
};
