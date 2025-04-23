<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoGenreFilter
{
    public function handle ($query, Closure $next)
    {
        $genre = request()->header('X-Genre');

        if ($genre && is_numeric($genre)) {
            $query->whereHas('genre', function ($q) use ($genre) {
                $q->where('genre', 'like', '%' . $genre . '%');
            });
        }

        return $next($query);
    }
}