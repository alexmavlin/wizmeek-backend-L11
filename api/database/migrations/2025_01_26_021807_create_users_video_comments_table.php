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
        Schema::create('users_video_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('User ID');
            $table->unsignedBigInteger('video_comment_id')->comment('YouTube Video ID');

            $table->foreign('user_id', 'user_comment_user_fk')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('video_comment_id', 'user_comment_comment_fk')->references('id')->on('video_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_video_comments');
    }
};
