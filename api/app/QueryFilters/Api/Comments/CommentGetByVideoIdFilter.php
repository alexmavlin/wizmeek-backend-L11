<?

namespace App\QueryFilters\Api\Comments;

use Closure;

class CommentGetByVideoIdFilter
{
    private $video_id;

    public function __construct($video_id)
    {
        $this->video_id = $video_id;
    }

    public function handle ($query, Closure $next)
    {
        $query->where('youtube_video_id', $this->video_id);
        $query->orWhereHas('youtubeVideo', function ($query) {
            $query->where('youtube_id', $this->video_id);
        });

        return $next($query);
    }
}