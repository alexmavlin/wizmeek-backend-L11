<?

namespace App\QueryFilters\Api\Users;

use Closure;

class UserProfileSelectFilter
{
    public function handle ($query, Closure $next)
    {
        $query->select(
            'id',
            'name',
            'email',
            'nickname',
            'avatar',
            'google_avatar',
            'created_at',
            'description'
        );

        return $next($query);
    }
}