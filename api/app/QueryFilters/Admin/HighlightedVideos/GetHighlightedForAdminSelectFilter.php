<?

namespace App\QueryFilters\Admin\HighlightedVideos;

use Closure;

class GetHighlightedForAdminSelectFilter
{
    public function handle ($query, Closure $next)
    {
        $query->select(
            'id',
            'video_id',
            'flag'
        );

        return $next($query);
    }
}