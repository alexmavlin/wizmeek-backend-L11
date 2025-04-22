<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoGetRelatedFilter
{
    private $youtube_id;

    public function __construct($youtube_id)
    {
        $this->youtube_id = $youtube_id;
    }

    public function handle($query, Closure $next)
    {
        $youtube_id = $this->youtube_id;

        $query->where(function ($q) use ($youtube_id) {
            $q->whereHas('artist', function ($q) use ($youtube_id) {
                $q->whereHas('youTubeVideos', function ($q) use ($youtube_id) {
                    $q->where('youtube_id', $youtube_id);
                });
            });
            $q->orWhereHas('genre', function ($q) use ($youtube_id) {
                $q->whereHas('youTubeVideos', function ($q) use ($youtube_id) {
                    $q->where('youtube_id', $youtube_id);
                });
            });
        });

        return $next($query);
    }
}
