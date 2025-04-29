<?

namespace App\QueryFilters\Admin\Artists;

use Closure;

class WithYouTubeVideosFilter
{
    public function handle ($query, Closure $next)
    {
        $query->with([
            'youTubeVideos' => function ($subQuery) {
                $subQuery->select('id', 'artist_id');
            }
        ]);

        return $next($query);
    }
}