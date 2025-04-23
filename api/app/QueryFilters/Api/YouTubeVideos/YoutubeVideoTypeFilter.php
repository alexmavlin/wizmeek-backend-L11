<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YoutubeVideoTypeFilter
{
    public function handle ($query, Closure $next)
    {
        $videoType = request()->header('X-Video-Type');

        if ($videoType) {
            $query->whereHas('contentType', function ($q) use ($videoType) {
                $q->where('name', 'like', '%' . $videoType . '%');
            });
        }

        return $next($query);
    }
}