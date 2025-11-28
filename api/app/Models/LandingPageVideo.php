<?php

namespace App\Models;

use App\Traits\DataTypeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageVideo extends Model
{
    use HasFactory, DataTypeTrait;

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

    /**
     * Retrieve landing page videos with related data.
     *
     * This method fetches landing videos along with their related artist, genre, and country data.
     * It also includes the count of users who liked each video.
     *
     * @return array The formatted landing videos data array.
     */
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

        $videos = $query->get();

        return self::buildLandingVideosDataArray($videos);
    }

    public function videos()
    {
        return $this->belongsTo(YouTubeVideo::class, 'video_id');
    }
}
