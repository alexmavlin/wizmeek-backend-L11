<?

namespace App\QueryFilters\Admin\FeedBack;

use Closure;

class FeedbackOrderFilter
{
    public function handle ($query, Closure $next)
    {
        $query->orderBy('created_at', 'DESC');

        return $next($query);
    }
}