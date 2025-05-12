<?

namespace App\QueryFilters\Admin\Artists;

use Closure;

class ArtistsLoadRelationsFilter
{
    public function handle ($query, Closure $next)
    {
        $query->with([
            'genres:id,genre',
            'countries:id,name'
        ]);

        return $next($query);
    }
}