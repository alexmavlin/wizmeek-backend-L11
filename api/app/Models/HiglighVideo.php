<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiglighVideo extends Model
{
    use HasFactory;

    protected $table = 'higligh_videos';
    protected $guarded = ['id'];

    public static function getEditorsPickVideos()
    {
        $query = self::query();
        $query->select('id', 'video_id', 'flag');
        $query->where('flag', 'editors_pick');
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

        $data = [];
        foreach ($videos as $video) {
            $data[] = [
                'id' => $video->video->id,
                'artist' => $video->video->artist->name,
                'title' => $video->video->title,
                'thumbnail' => $video->video->thumbnail
            ];
        }
        return $data;
    }

    public static function getNewVideos()
    {
        $query = self::query();
        $query->select('id', 'video_id', 'flag');
        $query->where('flag', 'new');
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

        $data = [];
        foreach ($videos as $video) {
            $data[] = [
                'id' => $video->video->id,
                'artist' => $video->video->artist->name,
                'title' => $video->video->title,
                'thumbnail' => $video->video->thumbnail
            ];
        }
        return $data;
    }
    public static function getThrowbackVideos()
    {
        $query = self::query();
        $query->select('id', 'video_id', 'flag');
        $query->where('flag', 'throwback');
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

        $data = [];
        foreach ($videos as $video) {
            $data[] = [
                'id' => $video->video->id,
                'artist' => $video->video->artist->name,
                'title' => $video->video->title,
                'thumbnail' => $video->video->thumbnail
            ];
        }
        return $data;
    }

    public static function getEditorsPickVideosForLoader($searchString = "")
    {
        $query = YouTubeVideo::query();

        $query->select('id', 'title', 'thumbnail', 'artist_id', 'editors_pick');
        $query->where('editors_pick', '1');
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

        $data = [];

        foreach ($videos as $video) {
            $data[] = [
                'id' => $video->id,
                'artist' => $video->artist->name,
                'title' => $video->title,
                'thumbnail' => $video->thumbnail
            ];
        }
        return $data;
    }

    public static function getNewVideosForLoader($searchString = "")
    {
        $query = YouTubeVideo::query();

        $query->select('id', 'title', 'thumbnail', 'artist_id', 'new');
        $query->where('new', '1');
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

        $data = [];

        foreach ($videos as $video) {
            $data[] = [
                'id' => $video->id,
                'artist' => $video->artist->name,
                'title' => $video->title,
                'thumbnail' => $video->thumbnail
            ];
        }
        return $data;
    }

    public static function getThrowbackVideosForLoader($searchString = "")
    {
        $query = YouTubeVideo::query();

        $query->select('id', 'title', 'thumbnail', 'artist_id', 'throwback');
        $query->where('throwback', '1');
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

        $data = [];

        foreach ($videos as $video) {
            $data[] = [
                'id' => $video->id,
                'artist' => $video->artist->name,
                'title' => $video->title,
                'thumbnail' => $video->thumbnail
            ];
        }
        return $data;
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
