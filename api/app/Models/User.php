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
}
