<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;
use Illuminate\Support\Facades\Auth;

class YouTubeVideoIncludeCommentsFilter
{
    public function handle ($query, Closure $next)
    {
        $query->with([
            'comments' => function ($q) {
                $q->limit(4);
                $q->orderBy('created_at', 'DESC');
                $q->select('id', 'content', 'user_id', 'youtube_video_id', 'created_at');
                $q->whereHas('user', function ($q) {
                    $q->select('id', 'deleted_at');
                    $q->where('deleted_at', null);
                });
                $q->with([
                    'user' => function ($q) {
                        $q->select('id', 'name', 'avatar', 'google_avatar');
                    }
                ]);

                if (Auth::check()) {
                    $q->with([
                        'userLikes' => function ($q) {
                            $q->where('user_id', Auth::user()->id);
                            $q->select('users_video_comments.id');
                        }
                    ]);
                }
                $q->withCount('userLikes');
            }
        ]);

        return $next($query);
    }
}