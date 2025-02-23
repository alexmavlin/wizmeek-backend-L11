<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;

class YouTubeVideoRestoreController extends Controller
{
    public function __invoke($id)
    {
        try {
            YouTubeVideo::restoreVideo($id);
            return redirect()->route('admin_youtube_video_index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to restore a video. Error: ' . $e->getMessage());
        }
    }
}
