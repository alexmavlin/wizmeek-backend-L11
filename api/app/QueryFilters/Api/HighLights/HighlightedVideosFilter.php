<?

namespace App\QueryFilters\Api\HighLights;

class HighlightedVideosFilter
{
    public function handle($query, \Closure $next)
    {
        return $next(
            $query->whereIn('flag', ['new', 'throwback', 'editors_pick'])
                  ->select('id', 'video_id', 'flag')
                  ->with([
                      "video" => function ($query) {
                          $query->select(
                              'id', 'country_id', 'content_type_id', 'genre_id', 'artist_id',
                              'youtube_id', 'thumbnail', 'editors_pick', 'new', 'throwback',
                              'title', 'release_date'
                          )->with([
                              'country:id,flag',
                              'genre:id,genre,color',
                              'artist:id,name',
                          ])->withCount('likedByUsers');
                      }
                  ])
        );
    }
}