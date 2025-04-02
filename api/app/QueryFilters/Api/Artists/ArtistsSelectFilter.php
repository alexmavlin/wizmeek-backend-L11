<?

namespace App\QueryFilters\Api\Artists;

use Closure;

class ArtistsSelectFilter {

    public function handle($query, Closure $next)
    {
        $query->select(
            'id',
            'name',
            'avatar',
            'short_description',
            'is_visible',
            'spotify_link',
            'apple_music_link',
            'instagram_link'
        );

        return $next($query);
    }
}