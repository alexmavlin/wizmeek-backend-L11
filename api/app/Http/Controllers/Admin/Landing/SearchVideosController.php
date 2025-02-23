<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;

class SearchVideosController extends Controller
{
    public function __invoke(Request $request)
    {
        $videos = YouTubeVideo::getForLoader($request->searchString);

        return response()->json($videos);
    }
}
