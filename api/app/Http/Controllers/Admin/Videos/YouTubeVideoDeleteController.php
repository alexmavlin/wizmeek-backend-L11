<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;

class YouTubeVideoDeleteController extends Controller
{
    public function __invoke(YouTubeVideo $video)
    {
        // Soft delete the artist
        $video->delete();

        // Redirect back with a success message
        return redirect()->route('admin_youtube_video_index')->with('success', "Artist $video->title deleted successfully.");
    }
}
