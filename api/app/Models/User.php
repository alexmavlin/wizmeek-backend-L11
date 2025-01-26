<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public static function bindGenreToUser($genre_id)
    {
        $user = self::find(Auth::user()->id);
        $user->genreTaste()->sync([$genre_id]);
    }

    public static function unbindGenreFromUser($genre_id)
    {
        $user = self::find(Auth::user()->id);
        $user->genreTaste()->detach([$genre_id]);
    }

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

    public static function getProfileDetailsForApiEdit() 
    {
        $query = self::query();

        $query->select(
            'id',
            'name',
            'nickname',
            'avatar',
            'google_avatar',
            'description',
            'created_at'
        );

        $user = $query->find(auth()->id());

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar ? asset('img/avatars/' . $user->avatar) : ($user->google_avatar ? $user->google_avatar : asset('img/artists/avatars/noAvatar.webp')),
            'description' => $user->description,
            'joined' => date('M Y', strtotime($user->created_at)),
            'following' => 15,
            'followed_by' => 165
        ];

        return $response;
    }

    public function likedVideos()
    {
        return $this->belongsToMany(
            YouTubeVideo::class,     // Related model
            'youtube_videos_likes',  // Pivot table
            'user_id',               // Foreign key on the pivot table for this model
            'video_id'               // Foreign key on the pivot table for the related model
        )->withTimestamps();         // Include timestamps if present
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
}
