<?

namespace App\QueryFilters;

use Closure;

class CommonSearchFilter 
{
    private string $filterExpression;
    private string $column;

    public function __construct($filterExpression, $column)
    {
        $this->filterExpression = $filterExpression;
        $this->column = $column;
    }

    public function handle($query, Closure $next)
    {
        if ($this->filterExpression) {
            $query->where("$this->column", 'like', '%' . $this->filterExpression . '%');
        }

        return $next($query);
    }
}