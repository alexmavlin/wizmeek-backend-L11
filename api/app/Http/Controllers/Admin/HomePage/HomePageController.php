<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HiglighVideo;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function __invoke()
    {
        try {
            $editors_pick = HiglighVideo::getHighlightedForAdmin('editors_pick');
            $new = HiglighVideo::getHighlightedForAdmin('new');
            $throwback = HiglighVideo::getHighlightedForAdmin('throwback');
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to load Home Page Videos. Error: ' . $error->getMessage());
        }
        
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_create.scss',
                'resources/scss/admin/parts/linearPreloader.scss'
            ],
            "js" => [
                'resources/js/admin/functions/fetchVideosFolLoader.js'
            ],
            "editors_pick" => $editors_pick,
            "new" => $new,
            "throwback" => $throwback
        ];
        return view('admin.homepage.homepage', compact('data'));
    }
}
