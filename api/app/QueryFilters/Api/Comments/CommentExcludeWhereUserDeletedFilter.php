<?

namespace App\QueryFilters\Api\Comments;

use Closure;

class CommentExcludeWhereUserDeletedFilter
{
    public function handle ($query, Closure $next)
    {
        $query->whereHas('user', function ($q) {
            $q->select('id', 'deleted_at');
            $q->where('deleted_at', null);
        });

        return $next($query);
    }
}