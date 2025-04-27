<?

namespace App\QueryFilters\Api\Comments;

use Closure;

class CommentSearchFilter
{
    private string $searchLine;

    public function __construct($searchLine = '')
    {
        $this->searchLine = $searchLine;
    }

    public function handle ($query, Closure $next)
    {
        $query->where('content', 'like', '%' . $this->searchLine . '%');

        return $next($query);
    }
}