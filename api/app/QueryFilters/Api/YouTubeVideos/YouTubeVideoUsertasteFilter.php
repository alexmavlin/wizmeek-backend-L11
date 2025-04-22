<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;
use Illuminate\Support\Facades\Auth;

class YouTubeVideoUsertasteFilter
{
    public function handle ($query, Closure $next)
    {
        $user_id = Auth::user()->id;
        $query->whereHas('genre', function ($query) use ($user_id) {
            $query->whereHas('tasteUsers', function ($subQuery) use ($user_id) {
                $subQuery->where('users.id', $user_id);
            });
        });

        return $next($query);
    }
}