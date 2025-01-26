<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoComment extends Model
{
    use HasFactory;

    protected $table = "video_comments";
    protected $guarded = ['id'];

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
