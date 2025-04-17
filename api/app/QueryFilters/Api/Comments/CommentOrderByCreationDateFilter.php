<?

namespace App\QueryFilters\Api\Comments;

use Closure;

class CommentOrderByCreationDateFilter
{
    public function handle($query, Closure $next)
    {
        $query->orderBy('created_at', 'DESC');

        return $next($query);
    }
}