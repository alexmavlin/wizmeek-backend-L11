<?

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait MediaCardTrait
{
    /**
     * Get formatted media card data from paginated video results.
     *
     * This method processes a paginated collection of videos, formatting each video
     * into an array with relevant details, including artist information, links,
     * genre, likes, and additional metadata.
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginatedVideos Paginated collection of videos.
     * @return array Formatted media card data with pagination details.
     */
    private static function getMediaCardsData($paginatedVideos)
    {
        $response = $paginatedVideos->getCollection()->map(function ($video) {
            return [
                'id' => $video->id,
                'artist' => $video->artist->name,
                'apple_music_link' => $video->apple_music_link ? $video->apple_music_link : "",
                'country_flag' => asset($video->country->flag),
                'comments' => $video->comments ? self::getCommentsData($video->comments) : [],
                'isVideo' => $video->contentType?->name === 'Music Video',
                'isAudio' => $video->contentType?->name === 'Music Audio',
                'editors_pick' => $video->editors_pick ? true : false,
                'genre' => $video->genre ? $video->genre->genre : "NaN",
                'genre_color' => $video->genre->color,
                'isFavorite' => count($video->favoriteByUser) > 0 ? true : false,
                'isInProfile' => $video->in_user_profile_exists ? true : false,
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
        });

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

    /**
     * Format comment data into an array structure.
     *
     * This method processes a collection of comments, formatting each comment into 
     * a structured array with details including content, timestamp, like status, 
     * and user information.
     *
     * @param \Illuminate\Database\Eloquent\Collection|array $comments Collection or array of comment objects.
     * @return array Formatted array of comment data.
     */
    private static function getCommentsData($comments)
    {
        $reversedComments = $comments->sortBy('created_at', SORT_REGULAR);
        $data = [];
        foreach ($reversedComments as $comment) {
            array_push($data, [
                "_id" => $comment->id,
                "content" => $comment->content,
                "created" => strtotime($comment->created_at),
                "isLiked" => count($comment->userLikes) > 0 ? true : false,
                "nLike" => $comment->user_likes_count,
                "user" => [
                    "id" => $comment->user->id,
                    "name" => $comment->user->name,
                    "avatar" => $comment->user->avatar ? asset('img/avatars/' . $comment->user->avatar) : ($comment->user->google_avatar ? $comment->user->google_avatar : asset('img/artists/avatars/noAvatar.webp'))
                ]
            ]);
        }
        return $data;
    }

    /**
     * Modify a query to include related video comments and associated user data.
     *
     * This method appends comment-related data to a query, including the latest 
     * four comments, associated users, like counts, and whether the authenticated 
     * user has liked a comment.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @return void
     */
    private static function queryForVideoComments($query): void
    {
        $query->with([
            'comments' => function ($q) {
                $q->limit(4);
                $q->orderBy('created_at', 'DESC');
                $q->select('id', 'content', 'user_id', 'youtube_video_id', 'created_at');
                $q->whereHas('user', function ($q) {
                    $q->select('id', 'deleted_at');
                    $q->where('deleted_at', null);
                });
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
    }

    /**
     * Select specific columns for the video query.
     *
     * This method modifies the given query by selecting only the necessary 
     * columns from the database to optimize performance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @return void
     */
    private static function selectColumns($query): void
    {
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
    }

    /**
     * Load relationships for the video query.
     *
     * This method optimizes the query by specifying the necessary related 
     * models and selecting only essential columns to improve performance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @return void
     */
    private static function queryForRelations($query): void
    {
        $query->with([
            'artist:id,name',
            'country:id,flag',
            'genre:id,genre,color',
            'contentType:id,name'
        ]);
    }
}
