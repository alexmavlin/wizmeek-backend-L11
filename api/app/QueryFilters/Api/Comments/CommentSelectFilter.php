<?

namespace App\QueryFilters\Api\Comments;

use Closure;

class CommentSelectFilter
{
    public function handle ($query, Closure $next)
    {
        $query->select(
            'id', 
            'content', 
            'user_id', 
            'youtube_video_id', 
            'created_at'
        );

        return $next($query);
    }
}