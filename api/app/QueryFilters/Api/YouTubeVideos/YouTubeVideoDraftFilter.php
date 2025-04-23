<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoDraftFilter 
{
    public function handle ($query, Closure $next)
    {
        $query->where('is_draft', 0);

        return $next($query);
    }
}