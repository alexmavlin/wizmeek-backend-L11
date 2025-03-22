<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageVideo extends Model
{
    use HasFactory;

    protected $table = 'landing_page_videos';
    protected $guarded = ['id'];

    /**
     * Retrieves a list of artists with their associated videos for the landing page.
     *
     * This method fetches all artists and includes their related videos with details such as 
     * video ID, artist ID, thumbnail, and title. It also retrieves the artist name for each video.
     * The results are ordered in ascending order by artist ID.
     *
     * @return \Illuminate\Database\Eloquent\Collection The collection of artists with their associated videos.
     */
    public static function getForLanding()
    {
        $query = self::query();

        $query->with([
            'videos' => function ($q) {
                $q->select(
                    'id',
                    'artist_id',
                    'thumbnail',
                    'title',
                );
                $q->with([
                    'artist' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
            }
        ]);
        $query->orderBy('id', 'ASC');
        return $query->get();
    }

    public static function getLandingVideosApi()
    {
        $query = self::query();

        $query->with([
            'videos' => function ($query) {
                $query->select(
                    'id',
                    'country_id',
                    'genre_id',
                    'artist_id',
                    'youtube_id',
                    'thumbnail',
                    'editors_pick',
                    'new',
                    'throwback',
                    'title',
                    'release_date'
                );

                $query->with([
                    'country' => function ($q) {
                        $q->select('id', 'flag');
                    },
                    'genre' => function ($q) {
                        $q->select('id', 'genre', 'color');
                    },
                    'artist' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
                $query->withCount('likedByUsers');
            }
        ]);

        // Get the landing page videos
        $videos = $query->get();

        // Initialize response array
        $response = [];

        // Loop through the collection of landing page videos
        foreach ($videos as $landingPageVideo) {
            $video = $landingPageVideo->videos; // Single video from the 'belongsTo' relationship

            if ($video) {
                $response[] = [
                    'artist' => $video->artist->name ?? null,
                    'title' => $video->title,
                    'youtube_id' => $video->youtube_id,
                    'thumbnail' => $video->thumbnail,
                    'release_year' => date('Y', strtotime($video->release_date)),
                    'isFavorite' => false,
                    'isLiked' => false,
                    'nLike' => $video->liked_by_users_count,
                    'genre' => $video->genre->genre ?? null,
                    'genre_color' => $video->genre->color,
                    'country_flag' => $video->country ? asset($video->country->flag) : null,
                    'editors_pick' => $video->editors_pick ? true : false,
                    'new' => $video->new ? true : false,
                    'throwback' => $video->throwback ? true : false
                ];
            }
        }

        return $response;
    }

    public function videos()
    {
        return $this->belongsTo(YouTubeVideo::class, 'video_id');
    }
}
