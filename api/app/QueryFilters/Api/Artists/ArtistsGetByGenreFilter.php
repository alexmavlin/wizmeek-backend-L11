<?

namespace App\QueryFilters\Api\Artists;

use Closure;

class ArtistsGetByGenreFilter {

    public function handle($query, Closure $next)
    {
        $genreId = request()->header('X-Genre');

        if ((int) $genreId) {
            $query->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genres.id', (int) $genreId);
            });
        }

        return $next($query);
    }
}