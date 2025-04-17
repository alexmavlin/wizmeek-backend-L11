<?

namespace App\QueryFilters\Api\Users;

use Closure;

class UserVisitorSelectFilter
{
    public function handle ($query, Closure $next)
    {
        $query->select(
            'id',
            'name',
            'avatar',
            'google_avatar',
            'created_at',
            'description'
        );

        return $next($query);
    }
}