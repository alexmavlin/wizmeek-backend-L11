<?

namespace App\QueryFilters\Api\Artists;

use Closure;

class ArtistsWithCountriesFilter {

    public function handle($query, Closure $next)
    {
        $query->with([
            "countries" => function ($query) {
                $query->select('id', 'name', 'code', 'flag');
            }
        ]);

        return $next($query);
    }
}