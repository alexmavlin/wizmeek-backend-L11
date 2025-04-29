<?

namespace App\QueryFilters\Admin\HighlightedVideos;

use Closure;

class GetHighlightedVideoRelationsFilter
{
    public function handle ($query, Closure $next)
    {
        $query->with([
            'video' => function ($query) {
                $query->select('id', 'title', 'artist_id', 'thumbnail');
                $query->with([
                    'artist' => function ($query) {
                        $query->select('id', 'name');
                    }
                ]);
            }
        ]);

        return $next($query);
    }
}