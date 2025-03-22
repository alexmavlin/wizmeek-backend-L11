<?

namespace App\Traits;

trait DataTypeTrait {
    /**
     * Transforms a collection of videos into a structured array format.
     *
     * This method maps a given collection of video objects, extracting relevant details
     * such as ID, artist name, title, and thumbnail, and returns the data as an array.
     *
     * @param \Illuminate\Support\Collection $videos A collection of video objects.
     * @return array The transformed video data, including ID, artist name, title, and thumbnail.
     */
    private static function getHighlightedDatatype($videos): array
    {
        return $videos->map(function ($video) {
            return [
                'id' => $video->video->id,
                'artist' => $video->video->artist->name,
                'title' => $video->video->title,
                'thumbnail' => $video->video->thumbnail
            ];
        })->toArray();
    }
}