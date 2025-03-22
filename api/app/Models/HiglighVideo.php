<?php

namespace App\Models;

use App\Traits\DataTypeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiglighVideo extends Model
{
    use HasFactory, DataTypeTrait;

    protected $table = 'higligh_videos';
    protected $guarded = ['id'];

    /**
     * Retrieves a list of highlighted videos for the admin panel based on a given flag.
     *
     * This method fetches videos that match the specified flag, including their related 
     * video details such as title, artist, and thumbnail. The results are then formatted 
     * using the `getHighlightedDatatype` method.
     *
     * @param string $flag The flag used to filter highlighted videos.
     * @return array The list of highlighted videos, including their ID, artist name, title, and thumbnail.
     */
    public static function getHighlightedForAdmin($flag): array
    {
        $query = self::query();
        $query->select('id', 'video_id', 'flag');
        $query->where('flag', $flag);
        $query->with([
            'video' => function ($query) {
                $query->select('id', 'title', 'artist_id', 'thumbnail');
                $query->with([
                    'artist' => function ($query) {
                        $query->select('id', 'name');
                    }
                ]);
            }
        ]);
        $query->orderBy('id', 'ASC');
        $videos = $query->get();

        return self::getHighlightedDatatype($videos);
    }

    /**
     * Retrieves a list of highlighted YouTube videos based on a flag and optional search criteria.
     *
     * This method fetches videos that match the given flag and optionally filters them 
     * based on a search string. It includes related artist details and limits the results to three videos.
     * The data is then formatted using the `getHighlightedDatatype` method.
     *
     * @param string $flag The flag used to filter highlighted videos.
     * @param string|null $searchString An optional search term to filter videos by title or artist name.
     * @return array The filtered and formatted list of highlighted videos, including ID, title, thumbnail, and artist name.
     */
    public static function getHighlightedForLoader($flag, $searchString): array
    {
        $query = YouTubeVideo::query();

        $query->select('id', 'title', 'thumbnail', 'artist_id', 'editors_pick');
        $query->where("$flag", '1');
        if ($searchString) {
            $query->where(function ($q) use ($searchString) {
                $q->where('title', 'like', '%' . $searchString . '%')
                    ->orWhereHas('artist', function ($q) use ($searchString) {
                        $q->where('name', 'like', '%' . $searchString . '%');
                    });
            });
        }
        $query->with([
            'artist' => function ($q) {
                $q->select('id', 'name');
            }
        ]);
        $query->orderBy('id', 'ASC');
        $query->limit(3);

        $videos = $query->get();

        return self::getHighlightedDatatype($videos);
    }

    /**
     * Retrieve highlighted video categories for the API.
     *
     * @return array An array containing highlights for 'new', 'throwback', and 'editors_pick' categories.
     */
    public static function getHighlightsApi(): array
    {
        return [
            self::queryForHighlightsApi('new'),
            self::queryForHighlightsApi('throwback'),
            self::queryForHighlightsApi('editors_pick')
        ];
    }

    /**
     * Query and retrieve highlighted videos for a given flag.
     *
     * @param string $flag The flag used to filter highlighted videos (e.g., 'new', 'throwback', 'editors_pick').
     * @return array An array containing the flag and corresponding highlighted videos.
     */
    private static function queryForHighlightsApi($flag): array
    {
        $items = self::where('flag', $flag)
            ->select('id', 'video_id', 'flag')
            ->with([
                "video" => function ($query) {
                    $query->select(
                        'id',
                        'country_id',
                        'content_type_id',
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
                        },
                    ]);
                    $query->withCount('likedByUsers');
                }
            ])
            ->get();

        $data = [
            "flag" => $flag,
            "items" => self::buildHighlightsInstanceDataArray($items)
        ];

        return $data;
    }

    public function video()
    {
        return $this->belongsTo(YouTubeVideo::class, 'video_id');
    }
}
