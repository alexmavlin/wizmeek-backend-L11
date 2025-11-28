<?php

namespace App\Http\Controllers\Api\Highlights;

use App\Http\Controllers\Controller;
use App\Models\HighlightVideo;
use Illuminate\Http\Request;

class HomePageHighlightsController extends Controller
{
    public function __invoke()
    {
        $highlights = HighlightVideo::getHighlightsApi();

        return response()->json($highlights);
    }
}
