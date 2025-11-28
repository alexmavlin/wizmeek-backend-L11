<?php

namespace App\Http\Controllers\Api\Videos;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;

class GetUsersFavoriteVideosController extends Controller
{
    public function __invoke(Request $request)
    {
        $videos = YouTubeVideo::getFavoriteVideos($request);

        return response()->json($videos);
    }
}
