<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;
use Illuminate\Support\Facades\Auth;

class YouTubeVideoProfileAttachedFilter
{
    public function handle ($query, Closure $next)
    {
        $withProfileAttached = request()->header('X-With-Profile-Attached');

        if ($withProfileAttached && Auth::check()) {
            $query->withExists([
                'inUserProfile' => function ($q) {
                    $q->where('user_id', Auth::user()->id);
                }
            ]);
        }

        return $next($query);
    }
}