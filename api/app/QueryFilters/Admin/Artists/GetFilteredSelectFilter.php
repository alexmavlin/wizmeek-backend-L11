<?

namespace App\QueryFilters\Admin\Artists;

use Closure;

class GetFilteredSelectFilter
{
    public function handle ($query, Closure $next)
    {
        $query->select(
            'id',
            'name',
            'is_visible',
            'avatar'
        );

        return $next($query);
    }
}