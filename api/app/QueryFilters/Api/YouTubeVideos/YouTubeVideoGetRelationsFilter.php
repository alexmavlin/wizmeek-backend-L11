<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoGetRelationsFilter
{
    public function handle ($query, Closure $next)
    {
        $query->with([
            'artist:id,name',
            'country:id,flag',
            'genre:id,genre,color',
            'contentType:id,name'
        ]);

        return $next($query);
    }
}