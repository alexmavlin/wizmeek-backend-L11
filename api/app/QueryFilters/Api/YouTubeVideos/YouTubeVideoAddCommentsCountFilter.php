<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoAddCommentsCountFilter 
{
    public function handle($query, Closure $next)
    {
        $query->withCount('comments');

        return $next($query);
    } 
}