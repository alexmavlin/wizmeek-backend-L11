<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\VideoComment;
use App\Models\YouTubeVideo;
use Exception;
use Illuminate\Support\Facades\Log;

class YouTubeVideoShowController extends Controller
{
    public function __invoke($id)
    {
        try {
            $data = [
                "scss" => [
                    'resources/scss/admin/artists/artists_view.scss',
                    'resources/scss/admin/artists/artists_index.scss',
                    'resources/scss/admin/videos/video_view.scss',
                ],
                "js" => [],
                "video" => YouTubeVideo::getSingle($id, true),
                "comments" => VideoComment::getForAdminSingleVideo($id)
            ];
            // dd($data);
            return view('admin.videos.youtubevideoshow', compact('data'));
        } catch (Exception $error) {
            $message = "An error has occured during an attempt to access video details. Error: " . $error->getMessage();
            Log::error($message);
            return redirect()->back()->with('error', $message);
        }
    }
}
