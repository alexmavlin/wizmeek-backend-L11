<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Genre;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;

class YouTubeVideoDeletedIndexController extends Controller
{
    public function __invoke(Request $request) {
        // Fetch data needed for the view
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [],
            "videos" => YouTubeVideo::getDeleted(),
            "genres" => Genre::getForSelect(),
            "countries" => Country::getForSelect()
        ];

        // Return the view with the data
        return view('admin.videos.youtubevideosdeletedindex', compact('data'));
    }
}
