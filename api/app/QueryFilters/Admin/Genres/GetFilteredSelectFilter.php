<?

namespace App\QueryFilters\Admin\Genres;

use Closure;

class GetFilteredSelectFilter
{
    public function handle ($query, Closure $next)
    {
        $query->select(
            'id',
            'genre'
        );

        return $next($query);
    }
}