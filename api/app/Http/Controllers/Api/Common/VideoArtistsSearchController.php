<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Common\VideoArtistsSearchRequest;
use App\Models\Artist;
use App\Models\YouTubeVideo;

class VideoArtistsSearchController extends Controller
{
    public function __invoke(VideoArtistsSearchRequest $request)
    {
        $searchString = $request->search_string;
        
        try {
            $medias = YouTubeVideo::apiSearch($searchString);
            $artists = Artist::apiSearch($searchString);
            return response()->json([
                'success' => true,
                'message' => "Successfuly fetched data.",
                'error' => '',
                'data' => [
                    'medias' => $medias,
                    'artists' => $artists
                ]
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to load User's data.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
