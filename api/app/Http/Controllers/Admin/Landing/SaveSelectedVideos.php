<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\LandingPageVideo;
use Illuminate\Http\Request;

class SaveSelectedVideos extends Controller
{
    public function __invoke(Request $request)
    {
        $currentSelectedVideos = LandingPageVideo::get();

        if (count($currentSelectedVideos)) {
            foreach ($currentSelectedVideos as $video) {
                $video->delete();
            }
        }

        $newSelectedVideosIds = $request->all();

        for ($i = 1; $i < 4; $i++) {
            if (isset($newSelectedVideosIds["video_id_$i"])) {
                LandingPageVideo::create([
                    'video_id' => $newSelectedVideosIds["video_id_$i"],
                ]);
            }
        }
        return redirect()->back();
    }
}
