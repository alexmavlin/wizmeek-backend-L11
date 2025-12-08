<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;

class GetUserProfileVideosController extends Controller
{
    public function __invoke($uid)
    {
        try {
            $result = YouTubeVideo::getProfileVideos($uid);
            return response()->json([
                'success' => true,
                'message' => "Successfuly fetched user's profile videos",
                'error' => '',
                'data' => $result
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to fetch user's profile videos.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
