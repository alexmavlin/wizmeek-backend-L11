<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HiglighVideo;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function __invoke()
    {
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_create.scss',
                'resources/scss/admin/parts/linearPreloader.scss'
            ],
            "js" => [
                'resources/js/admin/functions/fetchVideosFolLoader.js'
            ],
            "editors_pick" => HiglighVideo::getEditorsPickVideos(),
            "new" => HiglighVideo::getNewVideos(),
            "throwback" => HiglighVideo::getThrowbackVideos()
        ];
        return view('admin.homepage.homepage', compact('data'));
    }
}
