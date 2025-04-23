<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;

class YouTubeVideoAddIsfavoriteFilter
{
    public function handle ($query, Closure $next)
    {
        if (Auth::check()) {
            $query->with([
                'favoriteByUser' => function ($q) {
                    $q->where('user_id', Auth::user()->id); // Filter for the logged-in user
                    $q->select('youtube_videos_favorites.id');
                }
            ]);
        }

        return $next($query);
    }
}