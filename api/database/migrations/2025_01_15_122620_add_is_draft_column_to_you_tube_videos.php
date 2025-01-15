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
        Schema::table('you_tube_videos', function (Blueprint $table) {
            $table->boolean('is_draft')->default(0)->after('throwback')->comment('Draft flag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('you_tube_videos', function (Blueprint $table) {
            $table->dropColumn('is_draft');
        });
    }
};
