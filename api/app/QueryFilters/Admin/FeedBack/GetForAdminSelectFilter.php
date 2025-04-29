<?

namespace App\QueryFilters\Admin\FeedBack;

use Closure;

class GetForAdminSelectFilter
{
    public function handle ($query, Closure $next)
    {
        $query->select(
            'id',
            'user_id',
            'subject',
            'message',
            'files',
            'unread',
            'created_at'
        );

        return $next($query);
    }
}