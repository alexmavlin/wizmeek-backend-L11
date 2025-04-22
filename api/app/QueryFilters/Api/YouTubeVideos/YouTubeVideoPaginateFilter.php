<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoPaginateFilter
{
    public function handle ($query, Closure $next)
    {
        $limit = request()->header('X-Limit', 3);

        $videos = $query->paginate($limit);

        return $next($videos);
    }
}