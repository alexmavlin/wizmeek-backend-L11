<?php

namespace App\Models;

use App\DataTransferObjects\Api\HighLights\HighlightedVideosDTO;
use App\QueryFilters\Admin\HighlightedVideos\GetHighlightedForAdminSelectFilter;
use App\QueryFilters\Admin\HighlightedVideos\GetHighlightedVideoRelationsFilter;
use App\QueryFilters\Admin\HighlightedVideos\GetHighlightedVideosByFlagFilter;
use App\QueryFilters\Api\HighLights\HighlightedVideosFilter;
use App\Traits\DataTypeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;

class HighlightVideo extends Model
{
    use HasFactory, DataTypeTrait;

    protected $table = 'higligh_videos';
    protected $guarded = ['id'];

    /**
     * Retrieves a list of highlighted videos for the admin panel based on a given flag using a pipeline.
     *
     * This method processes a video query through a Laravel Pipeline with the following stages:
     * - GetHighlightedForAdminSelectFilter: selects the required fields for the admin view.
     * - GetHighlightedVideosByFlagFilter: filters videos by the specified highlight flag (e.g., 'new', 'editors_pick').
     * - GetHighlightedVideoRelationsFilter: eager loads related models such as artist, title, and thumbnail.
     *
     * After filtering and loading relations, the results are ordered by ID in ascending order,
     * retrieved from the database, and passed to `getHighlightedDatatype` for formatting.
     *
     * @param string $flag The flag used to filter highlighted videos (e.g., 'new', 'throwback', 'editors_pick').
     * @return array The formatted list of highlighted videos, each including ID, artist name, title, and thumbnail URL.
     */

    public static function getHighlightedForAdmin($flag): array
    {
        $videos = app(Pipeline::class)
            ->send(self::query())
            ->through([
                GetHighlightedForAdminSelectFilter::class,
                new GetHighlightedVideosByFlagFilter($flag),
                GetHighlightedVideoRelationsFilter::class,
            ])
            ->thenReturn()
            ->orderBy('id', 'ASC')
            ->get();

        return self::getHighlightedDatatype($videos);
    }

    /**
     * Retrieves a list of highlighted YouTube videos based on a flag and optional search criteria.
     *
     * This method fetches videos that match the given flag and optionally filters them 
     * based on a search string. It includes related artist details and limits the results to three videos.
     * The data is then formatted using the `getHighlightedDatatype` method.
     *
     * @param string $flag The flag used to filter highlighted videos.
     * @param string|null $searchString An optional search term to filter videos by title or artist name.
     * @return array The filtered and formatted list of highlighted videos, including ID, title, thumbnail, and artist name.
     */
    public static function getHighlightedForLoader($flag, $searchString)
    {
        $query = YouTubeVideo::query();

        $query->select(
            'id',
            'title',
            'thumbnail',
            'artist_id',
            'editors_pick'
        );
        $query->where("$flag", '1');
        if ($searchString) {
            $query->where(function ($q) use ($searchString) {
                $q->where('title', 'like', '%' . $searchString . '%')
                    ->orWhereHas('artist', function ($q) use ($searchString) {
                        $q->where('name', 'like', '%' . $searchString . '%');
                    });
            });
        }
        $query->with([
            'artist' => function ($q) {
                $q->select('id', 'name');
            }
        ]);
        $query->orderBy('id', 'ASC');
        $query->limit(3);

        $videos = $query->get();

        // return $videos;
        return self::getHighlightedDatatypeForLoader($videos);
    }

    /**
     * Retrieve highlighted video categories for the API with caching.
     *
     * This method uses a cache key (`api_highlighted_videos`) to store the
     * results for 60 minutes, reducing database load. If the data is not
     * cached, it performs a single query through the `HighlightedVideosFilter`
     * pipeline to retrieve all highlighted videos. The results are then grouped
     * by their `flag` (e.g., 'new', 'throwback', 'editors_pick') and transformed
     * into an API-friendly format using a DTO.
     *
     * @return array An associative array of highlighted videos grouped by flag.
     */
    public static function getHighlightsApi(): array
    {
        $cacheKey = 'api_highlighted_videos';

        $highlights = Cache::remember($cacheKey, now()->addMinutes(60), function () {
            return app(Pipeline::class)
                ->send(self::query())
                ->through([
                    HighlightedVideosFilter::class
                ])
                ->thenReturn()
                ->get()
                ->groupBy('flag');
        });

        return HighlightedVideosDTO::fromGroupedCollection($highlights);
    }

    public function video()
    {
        return $this->belongsTo(
            YouTubeVideo::class,
            'video_id'
        );
    }
}
