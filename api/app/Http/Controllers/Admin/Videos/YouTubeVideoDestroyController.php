<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;

class YouTubeVideoDestroyController extends Controller
{
    public function __invoke($id)
    {
        $query = YouTubeVideo::query();

        $query->onlyTrashed();

        $video = $query->find($id);

        // dd($video);

        if (!$video) {
            return redirect()->back()->with('error', 'The video could not be found or has already been permanently deleted.');
        }

        try {
            $video->forceDelete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to remove the video. Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'The video has been permanently deleted.');
    }
}
