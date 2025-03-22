<?

namespace App\Traits;

trait DataTypeTrait
{
    /**
     * Transforms a collection of videos into a structured array format.
     *
     * This method maps a given collection of video objects, extracting relevant details
     * such as ID, artist name, title, and thumbnail, and returns the data as an array.
     *
     * @param \Illuminate\Support\Collection $videos A collection of video objects.
     * @return array The transformed video data, including ID, artist name, title, and thumbnail.
     */
    private static function getHighlightedDatatype($videos): array
    {
        return $videos->map(function ($video) {
            return [
                'id' => $video->video->id,
                'artist' => $video->video->artist->name,
                'title' => $video->video->title,
                'thumbnail' => $video->video->thumbnail
            ];
        })->toArray();
    }

    /**
     * Transforms a collection of artists into an API-friendly data structure.
     *
     * This method maps artist data to a structured array format for API responses,
     * including:
     * - Unique artist ID (`_id`)
     * - Static number of fans (`nFan`)
     * - Profile share link (`shareLink`)
     * - Artist avatar (`cover`)
     * - Name (`name`)
     * - Short biography (`bio`)
     * - Genres (`type`, merged genre names)
     *
     * @param \Illuminate\Support\Collection $artists Collection of artist models.
     * @return array Structured artist data array.
     */
    private static function getApiArtistsIndexDatatype($artists): array
    {
        return $artists->map(function ($artist) {
            return [
                '_id' => $artist->id,
                'nFan' => 250,
                'shareLink' => "https://example.com/profile/1",
                'cover' => asset($artist->avatar),
                'name' => $artist->name,
                'bio' => $artist->short_description,
                'type' => self::mergeGenreNames($artist->genres)
            ];
        })->toArray();
    }

    /**
     * Build an array of highlight video data instances.
     *
     * @param \Illuminate\Support\Collection $instances Collection of video highlight instances.
     * @return array An array containing structured highlight video data.
     */
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

    /**
     * Build an array of video data for the landing page.
     *
     * @param \Illuminate\Support\Collection $videos Collection of video instances.
     * @return array An array containing structured video data.
     */
    private static function buildLandingVideosDataArray($videos): array
    {
        return $videos->map(function ($landingPageVideo) {
            $video = $landingPageVideo->videos; // Single video from the 'belongsTo' relationship

            if (!$video) {
                return null;
            }

            return [
                'artist' => $video->artist->name ?? null,
                'title' => $video->title,
                'youtube_id' => $video->youtube_id,
                'thumbnail' => $video->thumbnail,
                'release_year' => date('Y', strtotime($video->release_date)),
                'isFavorite' => false,
                'isLiked' => false,
                'nLike' => $video->liked_by_users_count,
                'genre' => $video->genre->genre ?? null,
                'genre_color' => $video->genre->color ?? null,
                'country_flag' => $video->country ? asset($video->country->flag) : null,
                'editors_pick' => (bool) $video->editors_pick,
                'new' => (bool) $video->new,
                'throwback' => (bool) $video->throwback,
            ];
        })->filter()->values()->toArray();
    }

    /**
     * Build the profile details array for a guest user.
     *
     * This method formats the profile details of a user when viewed as a guest,
     * including their avatar, description, and follow statistics.
     *
     * @param \App\Models\User $user The user whose profile details are being retrieved.
     * @return array The formatted profile details.
     */
    private static function buildProfileDetailsAsguestDataArray($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar ? asset('img/avatars/' . $user->avatar) : ($user->google_avatar ? $user->google_avatar : asset('img/artists/avatars/noAvatar.webp')),
            'description' => $user->description,
            'joined' => date('M Y', strtotime($user->created_at)),
            'following' => $user->following_users_count,
            'followed_by' => $user->followed_by_users_count
        ];
    }

    /**
     * Build the profile details array for an authenticated user.
     *
     * This method formats the profile details of a logged-in user,
     * including their email, nickname, avatar, description, and follow statistics.
     *
     * @param \App\Models\User $user The authenticated user whose profile details are being retrieved.
     * @return array The formatted profile details.
     */
    private static function buildUserProfileDetailsDataArray($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nickname' => $user->nickname ?? "",
            'avatar' => $user->avatar ? asset('img/avatars/' . $user->avatar) : ($user->google_avatar ? $user->google_avatar : asset('img/artists/avatars/noAvatar.webp')),
            'description' => $user->description,
            'joined' => date('M Y', strtotime($user->created_at)),
            'following' => $user->following_users_count,
            'followed_by' => $user->followed_by_users_count
        ];
    }
}
