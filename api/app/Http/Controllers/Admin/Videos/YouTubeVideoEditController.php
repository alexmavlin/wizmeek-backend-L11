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
        try {
            $content_types = ContentType::getForSelect();
            $genres = Genre::getForSelect();
            $countries = Country::getForSelect();
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to get data for edited video. Error: ' . $error->getMessage());
        }
        
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_create.scss'
            ],
            "js" => [
            'resources/js/admin/youtubeGetVideoData.js'
            ],
            "content_types" => $content_types,
            "genres" => $genres,
            "countries" => $countries,
            "video" => $video
        ];
        return view('admin.videos.youtubevideoedit', compact('data'));
    }
}
