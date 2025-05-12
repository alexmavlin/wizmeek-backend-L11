<?

namespace App\QueryFilters\Admin\FeedBack;

use Closure;

class FeedbackLoadUserfilter
{
    public function handle ($query, Closure $next)
    {
        $query->with([
            'user' => function ($q) {
                $q->select(
                    'id',
                    'name',
                    'avatar',
                    'google_avatar'
                );
            }
        ]);

        return $next($query);
    }
}