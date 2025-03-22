<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchVideosController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $videos = YouTubeVideo::getForLoader($request->searchString);
        } catch (\Exception $error) {
            Log::error('Error fetching videos: ' . $error->getMessage(), ['exception' => $error]);
            $videos = [];
        }

        return response()->json($videos);
    }
}
