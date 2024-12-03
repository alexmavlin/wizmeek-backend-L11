<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HiglighVideo;
use Illuminate\Http\Request;

class SaveHomePageHighlightsController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd("save");
        $request = $request->all();
        // dd($request);
        $currentEditorsPickVideos = HiglighVideo::where('flag', 'editors_pick')->orderBy('id', 'ASC')->get();
        $currentNewVideos = HiglighVideo::where('flag', 'new')->orderBy('id', 'ASC')->get();
        $currentThrowbackVideos = HiglighVideo::where('flag', 'throwback')->orderBy('id', 'ASC')->get();

        $areChangesProvidedForEditorsPickVideos = false;
        $areChangesProvidedForNewVideos = false;
        $areChangesProvidedForThrowbackVideos = false;
        for ($i = 1; $i <= 5; $i++) {
            if (
                isset($request["editors_pick_video_id_$i"])
                && $request["editors_pick_video_id_$i"] != null
                && isset($currentEditorsPickVideos[$i - 1])
                && $currentEditorsPickVideos[$i - 1]->video_id != $request["editors_pick_video_id_$i"]
            ) {
                $areChangesProvidedForEditorsPickVideos = true;
            } elseif (
                isset($request["editors_pick_video_id_$i"])
                && $request["editors_pick_video_id_$i"] != null
                && !isset($currentEditorsPickVideos[$i - 1])
                ) {
                    $areChangesProvidedForEditorsPickVideos = true;
            }

            if (
                isset($request["new_video_id_$i"])
                && $request["new_video_id_$i"] != null
                && isset($currentNewVideos[$i - 1])
                && $currentNewVideos[$i - 1]->video_id != $request["new_video_id_$i"]
            ) {
                $areChangesProvidedForNewVideos = true;
            } elseif (
                isset($request["new_video_id_$i"])
                && $request["new_video_id_$i"] != null
                && !isset($currentNewVideos[$i - 1])
            ) {
                $areChangesProvidedForNewVideos = true;
            }

            if (
                isset($request["throwback_video_id_$i"])
                && $request["throwback_video_id_$i"] != null
                && isset($currentThrowbackVideos[$i - 1])
                && $currentThrowbackVideos[$i - 1]->video_id != $request["throwback_video_id_$i"]
            ) {
                $areChangesProvidedForThrowbackVideos = true;
            } elseif (
                isset($request["throwback_video_id_$i"])
                && $request["throwback_video_id_$i"] != null
                && !isset($currentThrowbackVideos[$i - 1])
                ) {
                // dd($currentThrowbackVideos[$i]->video_id);
                $areChangesProvidedForThrowbackVideos = true;
            }
        }

        // dd($areChangesProvidedForEditorsPickVideos, $areChangesProvidedForNewVideos, $areChangesProvidedForThrowbackVideos);

        if ($areChangesProvidedForEditorsPickVideos) {
            foreach ($currentEditorsPickVideos as $video) {
                $video->delete();
            }
            for ($i = 1; $i <= 5; $i++) {
                if (isset($request["editors_pick_video_id_$i"])) {
                    HiglighVideo::create([
                        'video_id' => $request["editors_pick_video_id_$i"],
                        'flag' => 'editors_pick'
                    ]);
                }
            }
        }

        if ($areChangesProvidedForNewVideos) {
            foreach ($currentNewVideos as $video) {
                $video->delete();
            }
            for ($i = 1; $i <= 5; $i++) {
                if (isset($request["new_video_id_$i"])) {
                    HiglighVideo::create([
                        'video_id' => $request["new_video_id_$i"],
                        'flag' => 'new'
                    ]);
                }
            }
        }

        if ($areChangesProvidedForThrowbackVideos) {
            foreach ($currentThrowbackVideos as $video) {
                $video->delete();
            }
            for ($i = 1; $i <= 5; $i++) {
                if (isset($request["throwback_video_id_$i"])) {
                    HiglighVideo::create([
                        'video_id' => $request["throwback_video_id_$i"],
                        'flag' => 'throwback'
                    ]);
                }
            }
        }

        return redirect()->back();
    }
}
