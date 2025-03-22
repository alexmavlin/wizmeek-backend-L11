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

    public static function getHighlightsApi()
    {
        $news = self::getNews();
        $throwbacks = self::getThrowbacks();
        $editorsPicks = self::getEditorsPicks();
        $data = [
            $news,
            $throwbacks,
            $editorsPicks
        ];
        return $data;
    }

    private static function getThrowbacks()
    {
        $items = self::where('flag', 'throwback')
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
            "flag" => "throwback",
            "items" => self::buildHighlightsInstanceDataArray($items)
        ];

        return $data;
    }

    private static function getNews()
    {
        $items = self::where('flag', 'new')
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

        // dd($items);

        $data = [
            "flag" => "new",
            "items" => self::buildHighlightsInstanceDataArray($items)
        ];

        return $data;
    }

    private static function getEditorsPicks()
    {
        $items = self::where('flag', 'editors_pick')
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
            "flag" => "editors_pick",
            "items" => self::buildHighlightsInstanceDataArray($items)
        ];

        return $data;
    }

    private static function buildHighlightsInstanceDataArray($instances)
    {
        $data = [];
        foreach ($instances as $instance) {
            $data[] = [
                "cover" => $instance->video->thumbnail,
                //"title" => $instance->video->artist->name . " - " . $instance->video->title,
                'artist' => $instance->video->artist->name,
                'country_flag' => asset($instance->video->country->flag),
                'editors_pick' => $instance->video->editors_pick ? true : false,
                'genre' => $instance->video->genre ? $instance->video->genre->genre : "NaN",
                'genre_color' => $instance->video->genre->color,
                'isFavorite' => false,
                'isLiked' => false,
                'new' => $instance->video->new ? true : false,
                'nLikes' => $instance->video->liked_by_users_count,
                'nLike' => $instance->video->liked_by_users_count,
                'release_year' => date('Y', strtotime($instance->video->release_date)),
                'throwback' => $instance->video->throwback ? true : false,
                'thumbnail' => $instance->video->thumbnail,
                'title' => $instance->video->title,
                'youtube_id' => $instance->video->youtube_id,
            ];
        }
        return $data;
    }


    public function video()
    {
        return $this->belongsTo(YouTubeVideo::class, 'video_id');
    }
}
