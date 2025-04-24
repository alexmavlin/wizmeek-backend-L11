<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Highlighted\SearchHighlightedVideosRequest;
use App\Models\HighlightVideo;
use Illuminate\Http\Request;

class SearchHighlightedVideosController extends Controller
{
    public function editorsPickVideos(SearchHighlightedVideosRequest $request) {
        $videos = HighlightVideo::getHighlightedForLoader('editors_pick', $request->searchString);
        return response()->json($videos);
    }

    public function newVideos(Request $request) {
        $videos = HighlightVideo::getHighlightedForLoader('new', $request->searchString);
        return response()->json($videos);
    }

    public function throwbackVideos(Request $request) {
        $videos = HighlightVideo::getHighlightedForLoader('throwback', $request->searchString);
        return response()->json($videos);
    }
}
