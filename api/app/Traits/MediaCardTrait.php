<?

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait MediaCardTrait
{
    private static function getMediaCardsData($paginatedVideos)
    {
        $response = $paginatedVideos->getCollection()->map(function ($video) {
            // dd($video);
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

        // dd($response);

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

    private static function getCommentsData($comments)
    {
        // dd($comments);
        $data = [];
        foreach ($comments as $comment) {
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

    private static function queryForVideoComments($query): void
    {
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
    }

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
