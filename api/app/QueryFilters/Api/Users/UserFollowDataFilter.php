<?

namespace App\QueryFilters\Api\Users;

use Closure;

class UserFollowDataFilter 
{
    public function handle ($query, Closure $next)
    {
        $query->withCount('followingUsers');
        $query->withCount('followedByUsers');

        return $next($query);
    }
}