<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VideoComment extends Model
{
    use HasFactory;

    protected $table = "video_comments";
    protected $guarded = ['id'];

    public static function getComment($id) {
        $query = self::query();

        $query->select('id', 'content', 'user_id', 'youtube_video_id', 'created_at');
        $query->with([
            'user' => function ($q) {
                $q->select('id', 'name', 'avatar', 'google_avatar');
            }
        ]);
        if (Auth::check()) {
            $query->with([
                'userLikes' => function ($q) {
                    $q->where('user_id', Auth::user()->id);
                    $q->select('users_video_comments.id');
                }
            ]);
        }
        $query->withCount('userLikes');

        $comment = $query->where('id', $id)->first();

        return [
            "_id" => $comment->id,
            "content" => $comment->content,
            "created" => date('d M Y', strtotime($comment->created_at)),
            "isLiked" => count($comment->userLikes) > 0 ? true : false,
            "nLike" => $comment->user_likes_count,
            "user" => [
                "name" => $comment->user->name,
                "avatar" => $comment->user->avatar ? asset('img/avatars/' . $comment->user->avatar) : ($comment->user->google_avatar ? $comment->user->google_avatar : asset('img/artists/avatars/noAvatar.webp'))
            ]
        ];
            
    }

    public function getContent()
    {
        return $this->content;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function youtubeVideo()
    {
        return $this->belongsTo(YouTubeVideo::class, 'youtube_video_id', 'id');
    }

    public function userLikes() {
        return $this->belongsToMany(User::class, 'users_video_comments', 'video_comment_id', 'user_id');
    }
}
