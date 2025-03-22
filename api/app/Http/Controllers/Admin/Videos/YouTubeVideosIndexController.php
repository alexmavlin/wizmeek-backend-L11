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
        try {
            $videos = YouTubeVideo::getVideosForIndex();
            $genres = Genre::getForSelect();
            $countries = Country::getForSelect();
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to load videos and data. Error: ' . $error->getMessage());
        }
        
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [

            ],
            "videos" => $videos,
            "genres" => $genres,
            "countries" => $countries,
        ];

        return view('admin.videos.youtubevideosindex', compact('data'));
    }
}
