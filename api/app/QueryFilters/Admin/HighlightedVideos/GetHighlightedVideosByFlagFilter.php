<? 

namespace App\QueryFilters\Admin\HighlightedVideos;

use Closure;

class GetHighlightedVideosByFlagFilter
{
    private string $flag;

    public function __construct($flag)
    {
        $this->flag = $flag;
    }

    public function handle ($query, Closure $next)
    {
        $query->where('flag', $this->flag);

        return $next($query);
    }
}