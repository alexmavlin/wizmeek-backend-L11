<?

namespace App\DataTransferObjects\Api\HighLights;

class HighlightedVideosDTO
{
    public static function fromGroupedCollection($grouped)
    {
        return collect(['new', 'throwback', 'editors_pick'])->map(function ($flag) use ($grouped) {
            $items = $grouped[$flag] ?? collect();

            return [
                'flag' => $flag,
                'items' => $items->map(function ($instance) {
                    $video = $instance->video;

                    return [
                        "cover" => $video->thumbnail,
                        "artist" => $video->artist->name,
                        "country_flag" => asset($video->country->flag),
                        "editors_pick" => (bool) $video->editors_pick,
                        "genre" => $video->genre->genre ?? "NaN",
                        "genre_color" => $video->genre->color ?? null,
                        "isFavorite" => false,
                        "isLiked" => false,
                        "new" => (bool) $video->new,
                        "nLikes" => $video->liked_by_users_count,
                        "nLike" => $video->liked_by_users_count,
                        "release_year" => date('Y', strtotime($video->release_date)),
                        "throwback" => (bool) $video->throwback,
                        "thumbnail" => $video->thumbnail,
                        "title" => $video->title,
                        "youtube_id" => $video->youtube_id,
                    ];
                })->toArray()
            ];
        })->toArray();
    }
}
