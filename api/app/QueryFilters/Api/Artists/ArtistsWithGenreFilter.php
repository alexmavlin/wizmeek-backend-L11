<?

namespace App\QueryFilters\Api\Artists;

use Closure;

class ArtistsWithGenreFilter {

    public function handle($query, Closure $next)
    {
        $query->with([
            "genres" => function ($query) {
                $query->select('genre')->distinct();
            }
        ]);

        return $next($query);
    }
}