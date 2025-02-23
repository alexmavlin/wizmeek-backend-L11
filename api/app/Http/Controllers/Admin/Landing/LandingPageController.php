<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\LandingPageVideo;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function __invoke()
    {
        $videos = LandingPageVideo::getForLanding();

        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_create.scss',
                'resources/scss/admin/parts/linearPreloader.scss'
            ],
            "js" => [
                'resources/js/admin/loaders/landing/fetchVideoResults.js'
            ],
            "videos" => $videos
        ];

        // dd($data);

        return view('admin.landing.landing', compact('data'));
    }
}
