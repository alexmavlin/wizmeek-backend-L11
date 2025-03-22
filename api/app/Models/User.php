<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\DataTypeTrait;
use App\Traits\MediaCardTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, MediaCardTrait, DataTypeTrait;

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

    /**
     * Retrieve profile details for a guest user.
     *
     * This method fetches the profile details of a user based on the given user ID.
     * It includes basic user information such as name, avatar, and description,
     * as well as the count of followers and following users.
     *
     * @param int $uid The ID of the user whose profile is being requested.
     * @return \Illuminate\Http\JsonResponse The profile details formatted as a JSON response.
     */
    public static function getProfileDetailsAsGuest($uid)
    {
        $query = self::query();

        $query->select(
            'id',
            'name',
            'avatar',
            'google_avatar',
            'created_at',
            'description'
        );
        $query->withCount('followingUsers');
        $query->withCount('followedByUsers');
        $user = $query->find($uid);

        $response = self::buildProfileDetailsAsguestDataArray($user);

        return response()->json($response);
    }

    /**
     * Retrieve the authenticated user's profile details.
     *
     * This method fetches the profile details of the currently authenticated user,
     * including personal information, avatar details, and follow counts.
     *
     * @return \Illuminate\Http\JsonResponse The user's profile details formatted as a JSON response.
     */
    public static function getProfileDetails()
    {
        $authUserId = Auth::user()->id;

        $query = self::query();
        $query->select(
            'id',
            'name',
            'email',
            'nickname',
            'avatar',
            'google_avatar',
            'created_at',
            'description'
        );
        $query->withCount('followingUsers');
        $query->withCount('followedByUsers');
        $user = $query->find($authUserId);

        $response = self::buildUserProfileDetailsDataArray($user);

        return response()->json($response);
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
        return $this->hasMany(VideoComment::class, 'user_id', 'id');
    }

    public function commentLikes()
    {
        return $this->belongsToMany(VideoComment::class, 'users_video_comments', 'user_id', 'video_comment_id');
    }

    public function followingUsers()
    {
        return $this->belongsToMany(User::class, 'users_follow_users', 'follower_user_id', 'followed_user_id');
    }

    public function followedByUsers()
    {
        return $this->belongsToMany(User::class, 'users_follow_users', 'followed_user_id', 'follower_user_id');
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
