<?

namespace App\QueryFilters\Api\Comments;

use Closure;

class CommentGetByIdFilter
{
    private int $cid;

    public function __construct($cid)
    {
        $this->cid = (int) $cid;
    }

    public function handle ($query, Closure $next)
    {
        $comment = $query->find($this->cid);

        return $next($comment);
    }
}