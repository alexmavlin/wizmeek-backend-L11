<?

namespace App\QueryFilters;

use Closure;

class CommonFindFilter
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function handle ($query, Closure $next)
    {
        $item = $query->findOrFail($this->id);

        return $next($item);
    }
}