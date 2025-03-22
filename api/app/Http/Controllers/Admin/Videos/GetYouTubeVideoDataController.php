<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use Google\Client;
use Google\Service\YouTube;
use Illuminate\Support\Facades\Log;

class GetYouTubeVideoDataController extends Controller
{
    public function __invoke()
    {
        $apiKey = 'AIzaSyB33hn15XnZIAUNr0xOwMtgpZSW5FyG_VE';

        $videoId = request()->input('id');

        if (!$videoId) {
            return response()->json([
                "error" => "Video Id Error.",
                "message" => "No video ID provided"
            ]);
        }

        // Initialize YouTube API client
        
        $ret = [];

        try {
            $client = new Client();
            $client->setDeveloperKey($apiKey);
            $service = new YouTube($client);
    
            $response = $service->videos->listVideos('snippet', ['id' => $videoId]);

            $ret = [
                "id" => $response->items[0]->id,
                "artist_name" => $response->items[0]->snippet->channelTitle,
                "song_title" => $response->items[0]->snippet->title,
                "thumbnail" => $response->items[0]->snippet->thumbnails->standard->url,
            ];
        } catch (\Exception $error) {
            Log::error('Error fetching YouTube video data: ' . $error->getMessage(), ['exception' => $error]);
        }


        // Return the response as JSON
        return response()->json($ret);
    }
}