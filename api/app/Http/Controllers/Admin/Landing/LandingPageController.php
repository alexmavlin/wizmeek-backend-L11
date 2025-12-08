<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\LandingPageVideo;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function __invoke()
    {
        try {
            $videos = LandingPageVideo::getForLanding();
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to load Landing Page Videos. Error: ' . $error->getMessage());
        }

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

        return view('admin.landing.landing', compact('data'));
    }
}
