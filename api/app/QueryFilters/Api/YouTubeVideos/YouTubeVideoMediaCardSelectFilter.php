<?

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoMediaCardSelectFilter
{
    public function handle ($query, Closure $next)
    {
        $query->select(
            'id',
            'apple_music_link',
            'country_id',
            'content_type_id',
            'genre_id',
            'artist_id',
            'youtube_id',
            'thumbnail',
            'editors_pick',
            'new',
            'spotify_link',
            'throwback',
            'title',
            'release_date',
            'is_draft'
        );

        return $next($query);
    }
}