<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoSortingModeFilter
{
    public function handle ($query, Closure $next)
    {
        $mode = request()->header('X-Mode', 'latest');

        switch ($mode) {
            case 'random':
                $query->inRandomOrder();
                break;
            case 'latest':
                $query->orderBy('updated_at', 'DESC');
                break;
            case 'views':
                $query->orderBy('views', 'DESC');
                break;
            default:
                $query->orderBy('updated_at', 'DESC');
                break;
        }

        return $next($query);
    }
}