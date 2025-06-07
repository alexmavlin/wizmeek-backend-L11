<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoGenreFilter
{
    public function handle ($query, Closure $next)
    {
        $genre = request()->header('X-Genre');

        // dd($genre);

        if ($genre && $genre !== 'All') {
            $query->whereHas('genre', function ($q) use ($genre) {
                $q->where('genre', 'like', '%' . $genre . '%');
            });
        }

        return $next($query);
    }
}