<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Genre;
use App\Traits\DataTypeTrait;
use App\Traits\MediaCardTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class YouTubeVideo extends Model
{
    use HasFactory, SoftDeletes, MediaCardTrait, DataTypeTrait;

    protected $table = 'you_tube_videos';
    protected $guarded = [];

    /**
     * Retrieves a paginated list of videos for the index page with optional filtering.
     *
     * This method fetches videos from the database while allowing filtering based on:
     * - Title or artist name
     * - Genre
     * - Country
     * - Flags (new, editors' pick, throwback)
     *
     * The results include related artist, genre, and country details.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator Paginated collection of videos.
     */
    public static function getVideosForIndex()
    {
        $query = self::query();

        // Fetch input values from the request
        $title = request('filter_expression');
        $genre = request('genre');
        $country = request('country');
        $flag = request('flags');
        /* $contentType = request('content_type'); */

        // Apply filters conditionally
        $query->when($title, function ($q, $title) {
            return $q->where('title', 'like', '%' . $title . '%')
                ->orWherehas('artist', function ($subQuery) use ($title) {
                    $subQuery->select('id', 'name');
                    $subQuery->where('name', 'like', '%' . $title . '%');
                });
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
            'throwback',
            'is_draft'
        );

        // Return paginated results ordered by creation date
        return $query->orderBy('updated_at', 'DESC')->paginate(10);
    }

    /**
     * Remove related selections for the current model instance.
     *
     * This method loads and deletes all related landing page items 
     * and highlight items associated with the model.
     *
     * @return void
     */
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

    /**
     * Retrieve the authenticated user's favorite videos.
     *
     * This method fetches the user's favorite videos, applies query filters,
     * and returns them in a structured format suitable for media cards.
     *
     * @param \Illuminate\Http\Request $request The request containing filter parameters.
     * @return array The formatted favorite videos data.
     */
    public static function getFavoriteVideos($request)
    {
        $user = Auth::user();

        $user->load(['favoriteVideos']);

        $favoriteVideoIds = $user->favoriteVideos->pluck('id');

        $preQuery = self::query()->whereIn('id', $favoriteVideoIds);

        $paginatedVideos = self::queryVideosForMediaCard($request, $preQuery);

        return self::getMediaCardsData($paginatedVideos);
    }

    /**
     * Retrieve and format videos for the API.
     *
     * This method queries videos based on the request parameters,
     * applies pagination, and returns them in a structured format
     * suitable for media cards.
     *
     * @param \Illuminate\Http\Request $request The request containing filter parameters.
     * @return array The formatted video data.
     */
    public static function getVideosApi($request)
    {
        $paginatedVideos = self::queryVideosForMediaCard($request);

        return self::getMediaCardsData($paginatedVideos);
    }

    public static function queryVideosForMediaCardByUserTaste($request, $preQuery = null)
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
            'release_date',
            'is_draft'
        );

        $query->where('is_draft', 0);

        $user_id = Auth::user()->id;
        $query->whereHas('genre', function ($query) use ($user_id) {
            $query->whereHas('tasteUsers', function ($subQuery) use ($user_id) {
                $subQuery->where('users.id', $user_id); // Correct table alias
            });
        });

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

        $query->with([
            'comments' => function ($q) {
                $q->limit(4);
                $q->orderBy('created_at', 'DESC');
                $q->select('id', 'content', 'user_id', 'youtube_video_id', 'created_at');
                $q->with([
                    'user' => function ($q) {
                        $q->select('id', 'name', 'avatar', 'google_avatar');
                    }
                ]);

                if (Auth::check()) {
                    $q->with([
                        'userLikes' => function ($q) {
                            $q->where('user_id', Auth::user()->id);
                            $q->select('users_video_comments.id');
                        }
                    ]);
                }
                $q->withCount('userLikes');
            }
        ]);

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

        // dd($query->paginate($limit));
        $paginatedVideos =  $query->paginate($limit);
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
        $withProfileAttached = $request->header('X-With-Profile-Attached');

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

        /* Select columns */
        self::selectColumns($query);
        /* // Select columns */

        $query->where('is_draft', 0);

        /* Get relations */
        self::queryForRelations($query);
        /* // Get relations */

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

        if ($withProfileAttached && Auth::check()) {
            $query->withExists([
                'inUserProfile' => function ($q) {
                    $q->where('user_id', Auth::user()->id);
                }
            ]);
        }

        $query->withCount('likedByUsers');

        /* Get Comments */
        self::queryForVideoComments($query);
        /* // Get Comments */

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

        // dd($query->paginate($limit));
        return $query->paginate($limit);
    }

    /**
     * Retrieves a list of videos based on a search string.
     *
     * This method fetches videos whose title matches the provided search string or whose associated 
     * artist's name matches the search string. The results include the video ID, title, thumbnail, 
     * and artist details. The data is then formatted using the `getHighlightedDatatype` method.
     *
     * @param string $searchString The search term used to filter videos by title or artist name.
     * @return array The filtered and formatted list of videos, including ID, title, thumbnail, and artist name.
     */
    public static function getForLoader($searchString): array
    {
        $query = self::query();
        $query->select('id', 'title', 'thumbnail', 'artist_id');
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

        return self::getHighlightedDatatype($videos);
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

    public static function apiSearch($searchString)
    {
        $query = Cache::remember("apiMediaSearch:$searchString", 600, function () use ($searchString) {
            return self::where('title', 'like', '%' . $searchString . '%')
                ->select('id', 'youtube_id', 'title', 'thumbnail', 'content_type_id', 'artist_id')
                ->with([
                    'artist' => function ($q) {
                        $q->select('id', 'name');
                    },
                    'contentType' => function ($q) {
                        $q->select('id', 'name');
                    }
                ])
                ->limit(3)
                ->get();
        });

        $response = $query->map(function ($video) {
            return [
                'id' => $video->id,
                'artist' => $video->artist->name,
                'thumbnail' => $video->thumbnail,
                'title' => $video->title,
                'youtube_id' => $video->youtube_id,
                'content_type' => $video->contentType->name
            ];
        });

        return $response;
    }

    public static function getSingle($youtube_id): array
    {
        $video = Cache::remember("singleYoutubeVideo:$youtube_id", 3600, function () use ($youtube_id) {
            $query = self::query();
            $query->where('youtube_id', $youtube_id);
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
                'release_date',
                'is_draft'
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

            return $query->first();
        });

        // dd($video);
        $sessionKey = 'viewed_video_' . $youtube_id;

        if (!session()->has($sessionKey)) {
            $video->timestamps = false;
            $video->increment('views');
            $video->timestamps = true;
            session([$sessionKey => true]);
        }

        return [
            'id' => $video->id,
            'artist' => $video->artist->name,
            'apple_music_link' => $video->apple_music_link ? $video->apple_music_link : "",
            'country_flag' => asset($video->country->flag),
            'comments' => [],
            'editors_pick' => $video->editors_pick ? true : false,
            'genre' => $video->genre ? $video->genre->genre : "NaN",
            'genre_color' => $video->genre->color,
            'isFavorite' => count($video->favoriteByUser) > 0 ? true : false,
            'isLiked' => count($video->likedByUsers) > 0 ? true : false,
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
    }

    public static function getRelatedForSingle($youtube_id, $request)
    {
        $page = $request->input('page', 1); // Get the current page, default to 1

        $cacheKey = "singleYouTubeVideoRelated:$youtube_id:page:$page"; // Different cache per page

        $relatedVideosPaginated = Cache::remember($cacheKey, 3600, function () use ($youtube_id, $page) {
            $query = self::query();

            $query->where('youtube_id', '!=', $youtube_id) // Exclude the current video
                ->where(function ($q) use ($youtube_id) {
                    $q->whereHas('artist', function ($q) use ($youtube_id) {
                        $q->whereHas('youTubeVideos', function ($q) use ($youtube_id) {
                            $q->where('youtube_id', $youtube_id);
                        });
                    })
                        ->orWhereHas('genre', function ($q) use ($youtube_id) {
                            $q->whereHas('youTubeVideos', function ($q) use ($youtube_id) {
                                $q->where('youtube_id', $youtube_id);
                            });
                        });
                });

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
                'release_date',
                'is_draft'
            );

            $query->with([
                'country:id,flag',
                'genre:id,genre,color',
                'artist:id,name',
            ]);

            if (Auth::check()) {
                $query->with([
                    'likedByUsers' => function ($q) {
                        $q->where('user_id', Auth::id())->select('youtube_videos_likes.id');
                    },
                    'favoriteByUser' => function ($q) {
                        $q->where('user_id', Auth::id())->select('youtube_videos_favorites.id');
                    }
                ]);
            }

            $query->withCount('likedByUsers');

            $query->with([
                'comments' => function ($q) {
                    $q->limit(4)
                        ->orderBy('created_at', 'DESC')
                        ->select('id', 'content', 'user_id', 'youtube_video_id', 'created_at')
                        ->with([
                            'user:id,name,avatar,google_avatar'
                        ]);
                    $q->orderBy('created_at', 'ASC');

                    if (Auth::check()) {
                        $q->with([
                            'userLikes' => function ($q) {
                                $q->where('user_id', Auth::id())->select('users_video_comments.id');
                            }
                        ]);
                    }
                    $q->withCount('userLikes');
                }
            ]);

            $query->orderBy('views', 'DESC');

            return $query->paginate(3); // Paginate properly
        });

        return self::getMediaCardsData($relatedVideosPaginated);
    }

    public static function getProfileVideos($user_id)
    {
        $query = self::query();

        $query->whereHas('inUserProfile', function ($query) use ($user_id) {
            $query->where('users.id', $user_id);
        });

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
            'release_date',
            'is_draft'
        );

        $query->with([
            'country:id,flag',
            'genre:id,genre,color',
            'artist:id,name',
        ]);

        if (Auth::check()) {
            $query->with([
                'likedByUsers' => function ($q) {
                    $q->where('user_id', Auth::id())->select('youtube_videos_likes.id');
                },
                'favoriteByUser' => function ($q) {
                    $q->where('user_id', Auth::id())->select('youtube_videos_favorites.id');
                }
            ]);
        }

        $query->withCount('likedByUsers');

        $query->with([
            'comments' => function ($q) {
                $q->limit(4)
                    ->orderBy('created_at', 'DESC')
                    ->select('id', 'content', 'user_id', 'youtube_video_id', 'created_at')
                    ->with([
                        'user:id,name,avatar,google_avatar'
                    ]);

                if (Auth::check()) {
                    $q->with([
                        'userLikes' => function ($q) {
                            $q->where('user_id', Auth::id())->select('users_video_comments.id');
                        }
                    ]);
                }
                $q->withCount('userLikes');
            }
        ]);

        $paginatedVideos = $query->paginate(6);

        return self::getMediaCardsData($paginatedVideos); // ✅ Move pagination here
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

    public function comments()
    {
        return $this->hasMany(VideoComment::class, 'youtube_video_id', 'id');
    }

    public function inUserProfile()
    {
        return $this->belongsToMany(
            User::class,
            'users_profile_videos',
            'video_id',
            'user_id'
        );
    }
}
