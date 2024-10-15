<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use App\Models\ContentType;
use App\Models\Country;
use App\Models\Genre;

class YouTubeVideoEditController extends Controller
{
    public function __invoke(YouTubeVideo $video)
    {
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_create.scss'
            ],
            "js" => [
            'resources/js/admin/youtubeGetVideoData.js'
            ],
            "content_types" => ContentType::getForSelect(),
            "genres" => Genre::getForSelect(),
            "countries" => Country::getForSelect(),
            "video" => $video
        ];
        return view('admin.videos.youtubevideoedit', compact('data'));
    }
}
