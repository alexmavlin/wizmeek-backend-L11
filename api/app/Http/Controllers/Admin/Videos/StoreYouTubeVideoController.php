<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Videos\StoreYouTubeVideoRequest;
use App\Models\Artist;
use App\Models\YouTubeVideo;

class StoreYouTubeVideoController extends Controller
{
    public function __invoke(StoreYouTubeVideoRequest $request)
    {
        // dd($request);
        $storeData = [
            'original_link' => $request->original_link,
            'youtube_id' => $request->youtube_id,
            'content_type_id' => (int) $request->content_type_id,
            'artist_id' => $this->getArtistId($request->artist_name),
            'title' => $request->title,
            'thumbnail' => $request->thumbnail,
            'release_date' => $this->makeDate($request->release_date),
            'genre_id' => (int) $request->genre_id,
            'country_id' => (int) $request->country_id,
            'editors_pick' => isset($request->editors_pick) ? 1 : 0,
            'new' => (isset($request->new) && $request->new == 'new') ? 1 : 0,
            'throwback' => (isset($request->new) && $request->new == 'throwback') ? 1 : 0
        ];

        $newVideo = YouTubeVideo::create($storeData);
        return redirect()->route('admin_youtube_video_index');
    }

    private function makeDate($date) {
        $dateString = strtotime("$date-01");
        $timestamp = date('Y-m-d H:i:s', $dateString);
        return $timestamp;
    }

    private function getArtistId($artistName) {
        $artist = Artist::where('name', $artistName)->select('id')->first();

        if ($artist) {
            return $artist->id;
        } else {
            $newArtistData = [
                'name' => $artistName,
                'avatar' => 'img/artists/avatars/noAvatar.webp'
            ];
            $newArtist = Artist::create($newArtistData);
            return $newArtist->id;
        }
    }
}
