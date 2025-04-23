<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoAddLikesCountFilter
{
    public function handle ($query, Closure $next)
    {
        $query->withCount('likedByUsers');

        return $next($query);
    }
}