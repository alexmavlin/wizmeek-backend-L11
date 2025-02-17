<?php

namespace App\Http\Controllers\Api\Videos;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetVideosByUserTasteController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => "Unable to fetch videos by a user's taste.",
                'error' => "No logged in users are found in the current session",
                'data' => []
            ], 401);
        }

        try {
            $videos = YouTubeVideo::queryVideosForMediaCardByUserTaste($request);

            return response()->json([
                'success' => true,
                'message' => "Successfuly fetched videos by user's taste",
                'error' => '',
                'data' => $videos
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to fetch videos by a user's taste.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
