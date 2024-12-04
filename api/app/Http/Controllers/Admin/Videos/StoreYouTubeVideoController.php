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
        // dd($request->all());
        // Prepare the data for storing or updating
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

        // Check for a soft-deleted video with the same youtube_id
        $existingVideo = YouTubeVideo::withTrashed()->where('youtube_id', $request->youtube_id)->first();

        if ($existingVideo) {
            // Restore the soft-deleted video and update its attributes
            $existingVideo->restore();
            $existingVideo->update($storeData);

            // Redirect with a success message
            return redirect()->route('admin_youtube_video_index')
                ->with('success', "The video '{$existingVideo->title}' has been restored and updated successfully.");
        }

        // If no soft-deleted video is found, create a new one
        $newVideo = YouTubeVideo::create($storeData);

        return redirect()->route('admin_youtube_video_index')
            ->with('success', "The video '{$newVideo->title}' has been added successfully.");
    }

    private function makeDate($date)
    {
        $dateString = strtotime("$date-01");
        return date('Y-m-d H:i:s', $dateString);
    }

    private function getArtistId($artistName)
    {
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