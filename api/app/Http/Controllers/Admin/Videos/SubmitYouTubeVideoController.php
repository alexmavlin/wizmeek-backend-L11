<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\ContentType;
use App\Models\Country;
use App\Models\Genre;
use Illuminate\Http\Request;

class SubmitYouTubeVideoController extends Controller
{
    public function __invoke()
    {
        try {
            $content_types = ContentType::getForSelect();
            $genres = Genre::getForSelect();
            $countries = Country::getForSelect();
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to get data for submit page. Error: ' . $error->getMessage());
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
            "countries" => $countries
        ];
        return view('admin.videos.submityoutubevideo', compact('data'));
    }
}
