<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Genre;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;

class YouTubeVideosIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [

            ],
            "videos" => YouTubeVideo::getVideosForIndex(),
            "genres" => Genre::getForSelect(),
            "countries" => Country::getForSelect()
        ];
        // dd($request);
        return view('admin.videos.youtubevideosindex', compact('data'));
    }
}
