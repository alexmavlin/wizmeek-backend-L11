<?

namespace App\QueryFilters\Api\Artists;

use Closure;

class ArtistsVisibleFilter {

    public function handle($query, Closure $next)
    {
        $query->where('is_visible', '1');

        return $next($query);
    }
}