<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoFlagFilter
{
    public function handle ($query, Closure $next)
    {
        $flag = request()->header('X-Video-Flag');

        if ($flag) {
            if (strtolower($flag) === 'new') {
                $query->where('new', 1);
            } elseif (strtolower($flag) === 'throwback') {
                $query->where('throwback', 1);
            }
        }

        return $next($query);
    }
}