<?

namespace App\QueryFilters\Admin\Artists;

use Closure;

class ArtistEditSelectFilter
{
    public function handle ($query, Closure $next)
    {
        $query->select(
            'id',
            'name',
            'is-visible',
            'avatar',
            'short_description',
            'full_description',
            'spotify_link',
            'apple_music_link',
            'instagram_link'
        );

        return $next($query);
    }
}