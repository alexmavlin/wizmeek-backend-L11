<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use Google\Client;
use Google\Service\YouTube;

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
        $client = new Client();
        $client->setDeveloperKey($apiKey);
        $service = new YouTube($client);

        // Example query to ensure connection to the API
        $response = $service->videos->listVideos('snippet', ['id' => $videoId]);

        $ret = [
            "id" => $response->items[0]->id,
            "artist_name" => $response->items[0]->snippet->channelTitle,
            "song_title" => $response->items[0]->snippet->title,
            "thumbnail" => $response->items[0]->snippet->thumbnails->standard->url,
        ];

        // Return the response as JSON
        return response()->json($ret);
    }
}