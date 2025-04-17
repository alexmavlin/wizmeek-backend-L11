<?

namespace App\QueryFilters\Api\Artists;

use Closure;

class ArtistsSearchSelectFilter {

    public function handle ($query, Closure $next)
    {
        $query->select(
            'id', 
            'name', 
            'avatar', 
            'is_visible'
        );

        return $next($query);
    }
}