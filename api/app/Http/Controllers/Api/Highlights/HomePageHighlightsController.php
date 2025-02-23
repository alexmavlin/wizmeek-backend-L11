<?php

namespace App\Http\Controllers\Api\Highlights;

use App\Http\Controllers\Controller;
use App\Models\HiglighVideo;
use Illuminate\Http\Request;

class HomePageHighlightsController extends Controller
{
    public function __invoke()
    {
        $highlights = HiglighVideo::getHighlightsApi();

        return response()->json($highlights);
    }
}
