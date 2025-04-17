<?

namespace App\QueryFilters\Api\Comments;

use Closure;

class CommentAddIsLikedFlagFilter
{
    protected $userId;

    public function __construct(?int $userId)
    {
        $this->userId = $userId;
    }

    public function handle($query, Closure $next)
    {
        if ($this->userId) {
            $query->with([
                'userLikes' => fn ($q) => $q->where('user_id', $this->userId)->select('users_video_comments.id')
            ]);
        }

        return $next($query);
    }
}