<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoArtistFilter
{
    public function handle ($query, Closure $next)
    {
        $artistId = request()->header('X-Artist');

        if ($artistId && is_numeric($artistId)) {
            $query->where('artist_id', $artistId);
        }

        return $next($query);
    }
}