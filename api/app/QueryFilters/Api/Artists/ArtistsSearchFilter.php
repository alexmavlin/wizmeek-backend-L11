<?

namespace App\QueryFilters\Api\Artists;

use Closure;

class ArtistsSearchFilter {
    
    protected string $search;

    public function __construct(string $search)
    {
        $this->search = $search;
    }

    public function handle($query, Closure $next)
    {
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $next($query);
    }
}