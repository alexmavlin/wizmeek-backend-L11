<?php

namespace App\QueryFilters\Api\Artists;

use Closure;

class ArtistsWithFollowersCountFilter
{
    public function handle($query, Closure $next)
    {
        $query->withCount('followers');

        return $next($query);
    }
}