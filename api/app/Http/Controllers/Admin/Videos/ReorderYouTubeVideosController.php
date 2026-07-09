<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReorderYouTubeVideosController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['ids'] as $index => $id) {
                    YouTubeVideo::where('id', $id)
                        ->update(['sort_order' => $index]);
                }
            });
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save the new order. Error: ' . $error->getMessage(),
            ], 500);
        }

        return response()->json(['success' => true]);
    }
}
