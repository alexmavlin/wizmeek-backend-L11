<?

namespace App\QueryFilters\Api\Genres;

use Closure;

class GenreSelectFilter
{
    public function handle($query, Closure $next)
    {
        return $next($query->select(
            'id', 
            'genre', 
            'color', 
            'img_link'
        ));
    }
}