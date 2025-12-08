<?php

namespace App\QueryFilters\Api\Artists;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IsArtistFollowedByUserFilter
{
    protected $user;

    public function __construct($user = null)
    {
        $this->user = $user;
    }

    public function handle($query, Closure $next)
    {
        // dd($this->user);
        if ($this->user) {
            $userId = $this->user->id;

            $query->addSelect([
                'is_followed_by_user' => DB::raw(
                    "EXISTS(
                        SELECT 1 FROM users_follow_artists 
                        WHERE users_follow_artists.artist_id = artists.id 
                        AND users_follow_artists.user_id = $userId
                    ) as is_followed_by_user"
                )
            ]);
        } else {
            $query->addSelect([
                'is_followed_by_user' => DB::raw('false as is_followed_by_user')
            ]);
        }

        return $next($query);
    }
}
