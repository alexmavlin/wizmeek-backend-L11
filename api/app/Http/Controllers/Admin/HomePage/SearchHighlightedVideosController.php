<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HiglighVideo;
use Illuminate\Http\Request;

class SearchHighlightedVideosController extends Controller
{
    public function editorsPickVideos(Request $request) {
        $videos = HiglighVideo::getEditorsPickVideosForLoader($request->searchString);
        return response()->json($videos);
    }

    public function newVideos(Request $request) {
        $videos = HiglighVideo::getNewVideosForLoader($request->searchString);
        return response()->json($videos);
    }

    public function throwbackVideos(Request $request) {
        $videos = HiglighVideo::getThrowbackVideosForLoader($request->searchString);
        return response()->json($videos);
    }
}
