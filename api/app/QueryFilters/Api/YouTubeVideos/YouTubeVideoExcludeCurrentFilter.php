<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoExcludeCurrentFilter
{
    private $youtube_id;

    public function __construct($youtube_id)
    {
        $this->youtube_id = $youtube_id;
    }

    public function handle ($query, Closure $next)
    {
        $query->where('youtube_id', '!=', $this->youtube_id);

        return $next($query);
    }
}