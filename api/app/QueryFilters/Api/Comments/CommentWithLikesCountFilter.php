<?php

namespace App\QueryFilters\Api\Comments;

use Closure;

class CommentWithLikesCountFilter
{
    public function handle ($query, Closure $next)
    {
        $query->withCount('userLikes');

        return $next($query);
    }
}