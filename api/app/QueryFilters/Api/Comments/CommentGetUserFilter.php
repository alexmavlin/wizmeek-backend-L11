<?

namespace App\QueryFilters\Api\Comments;

use Closure;

class CommentGetUserFilter
{
    public function handle ($query, Closure $next)
    {
        $query->with([
            'user' => function ($q) {
                $q->select(
                    'id', 
                    'name', 
                    'avatar', 
                    'google_avatar'
                );
            }
        ]);

        return $next($query);
    }
}