<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YouTubeVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'you_tube_videos';
    protected $guarded = [];

    public static function getVideosForIndex()
    {
        $query = self::query();

        // Fetch input values from the request
        $title = request('title');
        $genre = request('genre');
        $country = request('country');
        $flag = request('flags');
        /* $contentType = request('content_type'); */

        // Apply filters conditionally
        $query->when($title, function ($q, $title) {
            return $q->where('title', 'like', '%' . $title . '%');
        });

        $query->when($genre, function ($q, $genre) {
            return $q->where('genre_id', $genre);
        });

        $query->when($country, function ($q, $country) {
            return $q->where('country_id', $country);
        });

        $query->when($flag, function ($q, $flag) {
            if ($flag === 'new') {
                return $q->where('new', 1);
            } elseif ($flag === 'editors_pick') {
                return $q->where('editors_pick', 1);
            } elseif ($flag === 'throwback') {
                return $q->where('throwback', 1);
            }
        });

        /* $query->when($contentType, function($q, $contentType) {
            return $q->where('content_type_id', $contentType);
        }); */
        $query->with([
            'artist:id,name',
            'genre:id,genre,color',
            'country:id,flag,name'
        ]);

        $query->select(
            'id',
            'content_type_id',
            'artist_id',
            'title',
            'release_date',
            'thumbnail',
            'genre_id',
            'country_id',
            'editors_pick',
            'new',
            'throwback'
        );

        // Return paginated results ordered by creation date
        return $query->orderBy('updated_at', 'DESC')->paginate(10);
    }

    public function removeRelatedSelections()
    {
        $this->load(['landingItems', 'highlightItems']);

        foreach ($this->landingItems as $landingItem) {
            $landingItem->delete();
        }

        foreach ($this->highlightItems as $highlightItem) {
            $highlightItem->delete();
        }
    }

    public static function getFavoriteVideos($request)
    {
        $user = Auth::user();

        // Ensure the 'favoriteVideos' relationship is loaded
        $user->load(['favoriteVideos']);

        // Get the IDs of the favorite videos
        $favoriteVideoIds = $user->favoriteVideos->pluck('id');

        // Create a pre-query for the favorite videos
        $preQuery = self::query()->whereIn('id', $favoriteVideoIds);

        // Pass the pre-query into queryVideosForMediaCard
        $paginatedVideos = self::queryVideosForMediaCard($request, $preQuery);

        // Process the videos with getMediaCardsData
        return self::getMediaCardsData($paginatedVideos);
    }

    public static function getVideosApi($request)
    {
        // Paginate the query
        $paginatedVideos = self::queryVideosForMediaCard($request);

        // Format the response data
        return self::getMediaCardsData($paginatedVideos);
    }

    private static function queryVideosForMediaCard($request, $preQuery = null)
    {
        $limit = $request->header('X-Limit', 10);
        $mode = $request->header('X-Mode', 'latest');
        $genre = $request->header('X-Genre');
        $videoType = $request->header('X-Video-Type');
        $flag = $request->header('X-Video-Flag');
        $artistId = $request->header('X-Artist');

        $query = $preQuery ? $preQuery : self::query();

        if ($genre && strtolower($genre) != 'all') {
            $query->whereHas('genre', function ($q) use ($genre) {
                $q->where('genre', 'like', '%' . $genre . '%'); // assuming 'genre' is the column in genres table
            });
        }

        if ($videoType) {
            $query->whereHas('contentType', function ($q) use ($videoType) {
                $q->where('name', 'like', '%' . $videoType . '%');
            });
        }

        if ($flag) {
            if (strtolower($flag) === 'new') {
                $query->where('new', 1);
            } elseif (strtolower($flag) === 'throwback') {
                $query->where('throwback', 1);
            }
        }

        if ($artistId) {
            $query->where('artist_id', $artistId);
        }

        $query->select(
            'id',
            'apple_music_link',
            'country_id',
            'content_type_id',
            'genre_id',
            'artist_id',
            'youtube_id',
            'thumbnail',
            'editors_pick',
            'new',
            'spotify_link',
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

        if (Auth::check()) {
            $query->with([
                'likedByUsers' => function ($q) {
                    $q->where('user_id', Auth::user()->id); // Filter for the logged-in user
                    $q->select('youtube_videos_likes.id');
                }
            ]);
            $query->with([
                'favoriteByUser' => function ($q) {
                    $q->where('user_id', Auth::user()->id); // Filter for the logged-in user
                    $q->select('youtube_videos_favorites.id');
                }
            ]);
        }
        $query->withCount('likedByUsers');

        // Handle sorting based on mode
        switch ($mode) {
            case 'random':
                $query->inRandomOrder();
                break;
            case 'latest':
            default:
                $query->orderBy('updated_at', 'DESC');
                break;
        }

        return $query->paginate($limit);
    }

    private static function getMediaCardsData($paginatedVideos)
    {
        $response = $paginatedVideos->getCollection()->map(function ($video) {
            // dd($video);
            return [
                'id' => $video->id,
                'artist' => $video->artist->name,
                'apple_music_link' => $video->apple_music_link ? $video->apple_music_link : "",
                'country_flag' => asset($video->country->flag),
                'editors_pick' => $video->editors_pick ? true : false,
                'genre' => $video->genre ? $video->genre->genre : "NaN",
                'genre_color' => $video->genre->color,
                'isFavorite' => count($video->favoriteByUser) > 0 ? true : false,
                'isLiked' => count($video->likedByUsers) > 0 ? true : false ,
                'new' => $video->new ? true : false,
                'nLikes' => $video->liked_by_users_count,
                'nLike' => $video->liked_by_users_count,
                'release_year' => date('Y', strtotime($video->release_date)),
                'spotify_link' => $video->spotify_link ? $video->spotify_link : "",
                'throwback' => $video->throwback ? true : false,
                'thumbnail' => $video->thumbnail,
                'title' => $video->title,
                'youtube_id' => $video->youtube_id,
            ];
        });

        // Wrap the response with pagination metadata
        return [
            'data' => $response,
            'pagination' => [
                'total' => $paginatedVideos->total(),
                'per_page' => $paginatedVideos->perPage(),
                'current_page' => $paginatedVideos->currentPage(),
                'last_page' => $paginatedVideos->lastPage(),
                'next_page_url' => $paginatedVideos->nextPageUrl(),
                'prev_page_url' => $paginatedVideos->previousPageUrl(),
            ],
        ];
    }

    public static function getForLoader($searchString)
    {
        $query = self::query();
        //dd($searchString);
        $query->select('id', 'title', 'thumbnail', 'artist_id');
        // dd($query->get());
        $query->where('title', 'like', '%' . $searchString . '%');
        $query->orWhereHas('artist', function ($q) use ($searchString) {
            $q->where('name', 'like', '%' . $searchString . '%');
        });
        $query->with([
            'artist' => function ($q) {
                $q->select('id', 'name');
            }
        ]);
        $query->orderBy('id', 'ASC');

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

    public static function getDeleted()
    {
        $query = self::query();
        $query->onlyTrashed();

        // Fetch input values from the request
        $title = request('title');
        $genre = request('genre');
        $country = request('country');
        $flag = request('flags');
        /* $contentType = request('content_type'); */

        // Apply filters conditionally
        $query->when($title, function ($q, $title) {
            return $q->where('title', 'like', '%' . $title . '%');
        });

        $query->when($genre, function ($q, $genre) {
            return $q->where('genre_id', $genre);
        });

        $query->when($country, function ($q, $country) {
            return $q->where('country_id', $country);
        });

        $query->when($flag, function ($q, $flag) {
            if ($flag === 'new') {
                return $q->where('new', 1);
            } elseif ($flag === 'editors_pick') {
                return $q->where('editors_pick', 1);
            } elseif ($flag === 'throwback') {
                return $q->where('throwback', 1);
            }
        });

        $query->select(
            'id',
            'content_type_id',
            'artist_id',
            'title',
            'release_date',
            'thumbnail',
            'genre_id',
            'country_id',
            'editors_pick',
            'new',
            'throwback'
        );

        $query->with([
            'artist' => function ($q) {
                $q->withTrashed();
                $q->select('id', 'name');
            },
            'genre' => function ($q) {
                $q->withTrashed();
                $q->select('id', 'genre', 'color');
            },
            'country' => function ($q) {
                $q->withTrashed();
                $q->select('id', 'flag', 'name');
            }
        ]);

        $videos = $query->orderBy('created_at', 'DESC')->paginate(10);
        return $videos;
    }

    public static function restoreVideo($id)
    {
        // Query soft-deleted video
        $query = self::query()->onlyTrashed()->where('id', $id);

        // Include soft-deleted related models
        $query->with([
            'artist' => function ($q) {
                $q->onlyTrashed();
            },
            'genre' => function ($q) {
                $q->onlyTrashed();
            },
            'country' => function ($q) {
                $q->onlyTrashed();
            },
        ]);

        $video = $query->first();

        // Check if the video exists and is soft-deleted
        if (!$video) {
            throw new \Exception('The video could not be found or is not soft-deleted.');
        }

        // Restore related models if they exist and are soft-deleted
        if ($video->artist && $video->artist->trashed()) {
            $video->artist->restore();
        }
        if ($video->genre && $video->genre->trashed()) {
            $video->genre->restore();
        }
        if ($video->country && $video->country->trashed()) {
            $video->country->restore();
        }

        // Restore the video itself
        $video->restore();

        return $video;
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'id');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function landingItems()
    {
        return $this->hasMany(LandingPageVideo::class, 'video_id');
    }

    public function highlightItems()
    {
        return $this->hasMany(HiglighVideo::class, 'video_id');
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'youtube_videos_likes',
            'video_id',
            'user_id'
        )->withTimestamps();
    }

    public function favoriteByUser()
    {
        return $this->belongsToMany(
            User::class,
            'youtube_videos_favorites',
            'video_id',
            'user_id'
        )->withTimestamps();
    }

    public function contentType()
    {
        return $this->belongsTo(ContentType::class, 'content_type_id', 'id');
    }
}
