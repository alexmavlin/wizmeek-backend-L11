<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\DataTransferObjects\Api\UsersDTO\UserProfileDataDTO;
use App\DataTransferObjects\Api\UsersDTO\UserProfileDataForVisitorsDTO;
use App\QueryFilters\Api\Users\UserAddIsFollowedFlagFilter;
use App\QueryFilters\Api\Users\UserFollowDataFilter;
use App\QueryFilters\Api\Users\UserGetByIdFilter;
use App\QueryFilters\Api\Users\UserProfileSelectFilter;
use App\QueryFilters\Api\Users\UserVisitorSelectFilter;
use App\Traits\DataTypeTrait;
use App\Traits\MediaCardTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, MediaCardTrait, DataTypeTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the total number of registered users.
     *
     * @return int
     */
    public static function getTotalUsers(): int
    {
        return self::count();
    }

    /**
     * Get the number of users registered today.
     *
     * @return int
     */
    public static function getTodayRegisteredUsers(): int
    {
        return self::whereDate('created_at', Carbon::today())->count();
    }

    /**
     * Attach a genre to the authenticated user's taste preferences.
     *
     * This method associates a given genre with the currently logged-in user
     * by adding an entry in the pivot table for the `genreTaste` relationship.
     *
     * @param int $genre_id The ID of the genre to attach to the user.
     * @return void
     */
    public static function bindGenreToUser($genre_id)
    {
        $user = self::find(Auth::user()->id);
        $user->genreTaste()->attach([$genre_id]);
    }

    /**
     * Searches for artists by name with caching for optimized performance.
     *
     * This method retrieves artists whose names match the given search string,
     * limiting the results to three. The results are cached for 10 minutes (600 seconds)
     * to reduce database load.
     *
     * @param string $searchString The search query for artist names.
     * @return \Illuminate\Support\Collection A collection of artists with their ID, name, and avatar URL.
     */
    public static function apiSearch($searchString)
    {
        $query = Cache::remember("apiUserSearch:$searchString", 600, function () use ($searchString) {
            return self::where('name', 'like', '%' . $searchString . '%')
                ->select('id', 'name', 'avatar', 'google_avatar')
                ->limit(3)
                ->get();
        });

        $response = $query->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => asset($user->avatar) ? asset("img/avatars/$user->avatar") : $user->google_avatar,
            ];
        });

        return $response;
    }

    /**
     * Detach a genre from the authenticated user's taste preferences.
     *
     * This method removes the association between the currently logged-in user 
     * and a given genre by deleting the corresponding entry in the pivot table 
     * for the `genreTaste` relationship.
     *
     * @param int $genre_id The ID of the genre to detach from the user.
     * @return void
     */
    public static function unbindGenreFromUser($genre_id)
    {
        $user = self::find(Auth::user()->id);
        $user->genreTaste()->detach([$genre_id]);
    }

    /**
     * Toggle the like status of a video comment for the authenticated user.
     *
     * This method checks if the currently logged-in user has already liked a given 
     * video comment. If the user has liked the comment, it removes the like 
     * (unlikes it). Otherwise, it adds a like.
     *
     * @param int $comment_id The ID of the video comment to like or unlike.
     * @return string A message indicating whether the comment was liked or unliked.
     */
    public static function handleLikedVideoComment($comment_id)
    {
        $user = self::find(Auth::user()->id);

        $isLiked = $user->commentLikes()->where('video_comment_id', $comment_id)->exists();

        if ($isLiked) {
            $user->commentLikes()->detach($comment_id);
            return "Unliked video comment with id: " . $comment_id;
        } else {
            $user->commentLikes()->attach($comment_id);
            return "Liked video comment with id: " . $comment_id;
        }
    }

    /**
     * Toggle the like status of a video for the authenticated user.
     *
     * This method checks if the currently logged-in user has already liked a given 
     * video. If the user has liked the video, it removes the like (unlikes it). 
     * Otherwise, it adds a like.
     *
     * @param int $video_id The ID of the video to like or unlike.
     * @return string A message indicating whether the video was liked or unliked.
     */
    public static function handleLikedVideo($video_id)
    {
        $user = self::find(Auth::user()->id);

        $isLiked = $user->likedVideos()->where('video_id', $video_id)->exists();

        if ($isLiked) {
            $user->likedVideos()->detach($video_id);
            return "Unliked video with id: " . $video_id;
        } else {
            $user->likedVideos()->attach($video_id);
            return "Liked video with id: " . $video_id;
        }
    }

    /**
     * Toggle the favorite status of a video for the authenticated user.
     *
     * This method checks if the currently logged-in user has already added 
     * a given video to their favorites. If the video is already favorited, 
     * it removes it. Otherwise, it adds the video to favorites.
     *
     * @param int $video_id The ID of the video to favorite or unfavorite.
     * @return string A message indicating whether the video was added to or removed from favorites.
     */
    public static function handleFavoritedVideo($video_id)
    {
        $user = self::find(Auth::user()->id);

        $isLiked = $user->favoriteVideos()->where('video_id', $video_id)->exists();

        if ($isLiked) {
            $user->favoriteVideos()->detach($video_id);
            return "Video with id: " . $video_id . " was removed from favorites.";
        } else {
            $user->favoriteVideos()->attach($video_id);
            return "Video with id: " . $video_id . " was added to favorites.";
        }
    }

    public static function handleApiDeleteAccount($id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        if (Auth::id() !== $user->id) {
            throw new HttpException(403, 'Unauthorized.');
        }

        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $user->delete();

        return 'Your account has been deleted permanently.';
    }

    /**
     * Retrieve profile details for a guest user using pipeline processing and DTO transformation.
     *
     * This method uses a pipeline to filter and retrieve the profile details of a user
     * by the given user ID. The pipeline applies selection, follow data augmentation, and
     * additional flags such as "isFollowed". The final result is transformed into a DTO
     * and returned as a JSON response.
     *
     * @param int $uid The ID of the user whose profile is being requested.
     * @return \Illuminate\Http\JsonResponse The profile details formatted as a JSON response.
     */
    public static function getProfileDetailsAsGuest($uid)
    {
        $user = app(Pipeline::class)
            ->send(self::query())
            ->through([
                UserVisitorSelectFilter::class,
                UserFollowDataFilter::class,
                new UserGetByIdFilter($uid),
                new UserAddIsFollowedFlagFilter($uid)
            ])
            ->thenReturn();

        $response = UserProfileDataForVisitorsDTO::fromModel($user);

        return response()->json($response);
    }

    /**
     * Retrieve the authenticated user's profile details using pipeline processing and DTO transformation.
     *
     * This method uses a pipeline to fetch and filter the profile data of the currently authenticated user.
     * It applies filters to include personal details, follow data, and other relevant fields. The result is 
     * transformed into a DTO and returned as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse The authenticated user's profile details formatted as a JSON response.
     */
    public static function getProfileDetails()
    {
        $authUserId = Auth::user()->id;

        $user = app(Pipeline::class)
            ->send(self::query())
            ->through([
                UserProfileSelectFilter::class,
                UserFollowDataFilter::class,
                new UserGetByIdFilter($authUserId)
            ])
            ->thenReturn();

        $response = UserProfileDataDTO::fromModel($user);

        return response()->json($response);
    }

    /**
     * Handle the follow action for the authenticated user via API.
     *
     * This method allows the authenticated user to follow another user by their ID.
     * It performs several validation checks:
     * - Ensures the user is authenticated.
     * - Prevents a user from following themselves.
     * - Verifies the existence of the target user.
     * 
     * If all checks pass, the target user is added to the authenticated user's following list
     * using `syncWithoutDetaching` to avoid duplication.
     *
     * @param int $id The ID of the user to follow.
     * @return string A success message upon successful follow.
     *
     * @throws \Exception If the user is unauthenticated, attempts to follow themselves, or if the target user does not exist.
     */
    public static function handleApiFollowUsers($id)
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception("Unauthorized.");
        }

        if ($id == $user->id) {
            throw new \Exception("You cannot follow yourself.");
        }

        if (!User::find($id)) {
            throw new \Exception("User not found.");
        }

        $user->followingUsers()->syncWithoutDetaching([$id]);

        return 'Success. User is followed by you.';
    }

    /**
     * Handle the unfollow action for the authenticated user via API.
     *
     * This method allows the authenticated user to unfollow another user by their ID.
     * It performs several validation checks:
     * - Ensures the user is authenticated.
     * - Prevents a user from unfollowing themselves.
     * - Verifies the existence of the target user.
     * 
     * If all checks pass, the target user is removed from the authenticated user's following list
     * using `detach` to remove the relationship.
     *
     * @param int $id The ID of the user to unfollow.
     * @return string A success message upon successful unfollow.
     *
     * @throws \Exception If the user is unauthenticated, attempts to unfollow themselves, or if the target user does not exist.
     */
    public static function handleApiUnfollowUsers($id)
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception("Unauthorized.");
        }

        if ($id == $user->id) {
            throw new \Exception("You cannot unfollow yourself.");
        }

        if (!User::find($id)) {
            throw new \Exception("User not found.");
        }

        $user->followingUsers()->detach($id);

        return 'Success. User has been unfollowed.';
    }

    public function likedVideos()
    {
        return $this->belongsToMany(
            YouTubeVideo::class,
            'youtube_videos_likes',
            'user_id',
            'video_id'
        )->withTimestamps();
    }

    public function favoriteVideos()
    {
        return $this->belongsToMany(
            YouTubeVideo::class,
            'youtube_videos_favorites',
            'user_id',
            'video_id'
        )->withTimestamps();
    }

    public function genreTaste()
    {
        return $this->belongsToMany(
            Genre::class,
            'users_genres_taste',
            'user_id',
            'genre_id'
        );
    }

    public function comments()
    {
        return $this->hasMany(
            VideoComment::class, 
            'user_id', 
            'id'
        );
    }

    public function commentLikes()
    {
        return $this->belongsToMany(
            VideoComment::class, 
            'users_video_comments', 
            'user_id', 
            'video_comment_id'
        );
    }

    public function followingUsers()
    {
        return $this->belongsToMany(
            User::class, 
            'users_follow_users', 
            'follower_user_id', 
            'followed_user_id'
        );
    }

    public function followedByUsers()
    {
        return $this->belongsToMany(
            User::class, 
            'users_follow_users', 
            'followed_user_id', 
            'follower_user_id'
        );
    }

    public function videosInProfile()
    {
        return $this->belongsToMany(
            YouTubeVideo::class,
            'users_profile_videos',
            'user_id',
            'video_id'
        );
    }
}
