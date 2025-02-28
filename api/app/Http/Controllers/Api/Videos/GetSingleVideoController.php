<?php

namespace App\Http\Controllers\Api\Videos;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;

class GetSingleVideoController extends Controller
{
    public function __invoke(string $id, Request $request)
    {
        try {
            $video = YouTubeVideo::getSingle($id);
            $relatedVideos = YouTubeVideo::getRelatedForSingle($id, $request);

            // dd($relatedVideos);

            return response()->json([
                'success' => true,
                'message' => "Successfuly fetched data.",
                'error' => '',
                'data' => [
                    'video' => $video,
                    'related_videos' => $relatedVideos
                ]
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to fetch data.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
