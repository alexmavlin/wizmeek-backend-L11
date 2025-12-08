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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('User Relation');
            $table->string('subject', 250)->comment('Subject');
            $table->text('message', 10000)->comment('User message');
            $table->json('files')->nullable()->comment('Array of uploaded files');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id', 'feedback_user_fk')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
