<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('you_tube_videos', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->index()->after('views');
        });

        // Backfill so existing rows get a sequential sort_order matching the
        // current home ordering (newest first). Lower sort_order = shown first.
        $videos = DB::table('you_tube_videos')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->pluck('id');

        foreach ($videos as $index => $id) {
            DB::table('you_tube_videos')
                ->where('id', $id)
                ->update(['sort_order' => $index]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('you_tube_videos', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
