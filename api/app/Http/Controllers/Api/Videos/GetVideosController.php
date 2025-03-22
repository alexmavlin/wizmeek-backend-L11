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
        switch ($request->header('X-mode')) {
            case 'landing':
                $videos = LandingPageVideo::getLandingVideosApi();
                break;
            default:
                $videos = YouTubeVideo::getVideosApi($request);
                break;
        }
        return response()->json($videos);
    }
}
