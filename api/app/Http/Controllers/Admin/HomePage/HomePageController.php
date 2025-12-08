<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HighlightVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{
    public function __invoke()
    {
        try {
            $editors_pick = HighlightVideo::getHighlightedForAdmin('editors_pick');
            $new = HighlightVideo::getHighlightedForAdmin('new');
            $throwback = HighlightVideo::getHighlightedForAdmin('throwback');
        
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
        } catch (\Exception $error) {
            $message = 'An error has occured during an attempt to load Home Page Videos. Error: ' . $error->getMessage();
            Log::error($message);
            return redirect()->back()->with('error', $message);
        }
    }
}
