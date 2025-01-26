<?php

namespace App\Http\Controllers\Api\Videos;

use App\Http\Controllers\Controller;
use App\Models\LandingPageVideo;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;

class GetVideosController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request->header('X-mode'));
        switch ($request->header('X-mode')) {
            case 'landing':
                $videos = LandingPageVideo::getLandingVideosApi();
                break;
            default:
                $videos = YouTubeVideo::getVideosApi($request);
                break;
        }
        // dd($videos);
        // Return JSON response
        return response()->json($videos);
    }
}
