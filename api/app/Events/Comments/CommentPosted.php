<?php

namespace App\Events\Comments;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CommentPosted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $comment;
    public $video_id;

    public function __construct($comment, $video_id)
    {
        $this->comment = $comment;
        $this->video_id = $video_id;

        Log::info('CommentPosted Event Fired', [
            'comment' => $this->comment,
            'video_id' => $this->video_id
        ]);
    }

    public function broadcastOn(): Channel
    {
        Log::info('CommentPosted - broadcastOn() method fired. Channel: (video.' . $this->video_id . ')');
        return new Channel('video.' . $this->video_id);
    }

    public function broadcastWith()
    {
        Log::info('CommentPosted - broadcastWith() method fired. With comment: ', ['comment' => $this->comment]);
        return ['comment' => $this->comment];
    }

    public function broadcastAs(): string
    {
        return 'comment.added';
    }
}
