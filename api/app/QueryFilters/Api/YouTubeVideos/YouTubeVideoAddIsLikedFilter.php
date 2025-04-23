<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;

class YouTubeVideoAddIsLikedFilter
{
    public function handle ($query, Closure $next)
    {
        if (Auth::check()) {
            $query->with([
                'likedByUsers' => function ($q) {
                    $q->where('user_id', Auth::user()->id);
                    $q->select('youtube_videos_likes.id');
                }
            ]);
        }

        return $next($query);
    }
}