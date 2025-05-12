<?

namespace App\QueryFilters;

use Closure;

class CommonSearchFilter 
{
    private string $filterExpression;
    private string $column;
    private array $relations;

    public function __construct(string $filterExpression, string $column, array $relations = [])
    {
        $this->filterExpression = $filterExpression;
        $this->column = $column;
        $this->relations = $relations;
    }

    public function handle($query, Closure $next)
    {
        if ($this->filterExpression) {
            $query->where("$this->column", 'like', '%' . $this->filterExpression . '%');
        }

        if (count($this->relations)) {
            foreach ($this->relations as $relation) {
                $relationName = $relation['name'];
                $filterColumn = $relation['column'];
                $query->orWhereHas("$relationName", function ($subQuery) use ($filterColumn) {
                    $subQuery->where("$filterColumn", 'like', '%' . $this->filterExpression . '%');
                });
            }
        }

        return $next($query);
    }
}