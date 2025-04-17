<?

namespace App\QueryFilters\Api\Users;

use Closure;

class UserGetByIdFilter
{
    private $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function handle ($query, Closure $next)
    {
        $user = $query->find($this->uid);

        return $next($user);
    }
}