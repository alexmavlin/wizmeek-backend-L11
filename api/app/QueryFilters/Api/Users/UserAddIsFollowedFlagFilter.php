<?

namespace App\QueryFilters\Api\Users;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserAddIsFollowedFlagFilter
{
    private $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }
    public function handle ($user, Closure $next)
    {
        $isFollowed = false;

        if (Auth::check()) {
            $authUser = Auth::user();
            $isFollowed = $authUser->followingUsers()->where('followed_user_id', $this->uid)->exists();
        }

        $user->is_followed = $isFollowed;

        return $next($user);
    }
}