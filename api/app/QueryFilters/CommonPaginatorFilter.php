<?

namespace App\QueryFilters;

use Closure;

class CommonPaginatorFilter
{
    private int $pagesCount;

    public function __construct(int $pagesCount = 6)
    {
        $this->pagesCount = $pagesCount;
    }

    public function handle ($query, Closure $next)
    {
        $list = $query->paginate($this->pagesCount);

        return $next($list);
    }
}