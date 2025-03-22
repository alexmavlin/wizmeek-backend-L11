<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Models\YouTubeVideo;
use Illuminate\Support\Facades\Log;

class YouTubeVideoDeleteController extends Controller
{
    public function __invoke(YouTubeVideo $video)
    {
        try {
            $video->removeRelatedSelections();

            $video->delete();

            return redirect()->route('admin_youtube_video_index')
                ->with('success', "Video '{$video->title}' deleted successfully.");
        } catch (\Exception $error) {
            Log::error('Error deleting YouTube video', [
                'video_id' => $video->id,
                'error' => $error->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while attempting to delete the video. Please try again later.');
        }
    }
}
