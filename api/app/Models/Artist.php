<?php

namespace App\Models;

use App\DataTransferObjects\Api\ArtistsDTO\ArtistIndexDTO;
use App\DataTransferObjects\Api\ArtistsDTO\ArtistSearchDTO;
use App\QueryFilters\Api\Artists\ArtistsGetByGenreFilter;
use App\QueryFilters\Api\Artists\ArtistsSearchFilter;
use App\QueryFilters\Api\Artists\ArtistsSearchSelectFilter;
use App\QueryFilters\Api\Artists\ArtistsSelectFilter;
use App\QueryFilters\Api\Artists\ArtistsVisibleFilter;
use App\QueryFilters\Api\Artists\ArtistsWithGenreFilter;
use App\Traits\DataTypeTrait;
use App\Traits\GenreTrait;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;

class Artist extends Model
{
    use HasFactory, SoftDeletes, GenreTrait, DataTypeTrait;

    protected $table = "artists";
    protected $guarded = [];

    /**
     * Get paginated artists with optional filtering.
     *
     * @param string $filterExpression
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function getFiltered($filterExpression = ''): LengthAwarePaginator
    {
        $query = self::query();

        if (!empty($filterExpression)) {
            $query->where('name', 'like', '%' . $filterExpression . '%');
        }

        $query->select('id', 'name', 'is_visible', 'avatar');

        return $query->paginate(10);
    }

    /**
     * Deletes an artist and their associated YouTube videos.
     *
     * This method retrieves the artist by ID along with their YouTube videos.
     * If the artist exists, all associated YouTube videos are deleted before deleting the artist.
     * If the artist is not found, an exception is thrown.
     *
     * @param int $id The ID of the artist to be deleted.
     * @throws \Exception If the artist is not found.
     * @return bool|null Returns `true` if the deletion was successful, `false` if it failed, or `null` if no deletion occurred.
     */
    public static function deleteArtist($id)
    {
        $query = self::query();

        $artist = $query->with([
            'youTubeVideos' => function ($q) {
                $q->select('id', 'artist_id');
            }
        ])->find($id);

        if (!$artist) {
            throw new Exception('The specified artist was not found.');
        }

        foreach ($artist->youTubeVideos as $video) {
            $video->delete();
        }

        return $artist->delete();
    }

    /**
     * Retrieves a cached list of visible artists filtered by genre, formatted for API response.
     *
     * This method extracts the genre ID from the `X-Genre` request header, applies caching based
     * on that genre, and processes the artist query through a series of pipeline filters:
     * selection, visibility, genre filtering, and genre relationship loading.
     * 
     * The resulting artist collection is then transformed into a structured API response
     * using the `ArtistIndexDTO`.
     *
     * @return array The formatted array of artists, including ID, name, avatar, description,
     *               genre types, and external profile/share links.
     */
    public static function getForApi(): array
    {
        $genreId = (string) request()->header('X-Genre') ?: (string) 'all';
        $cacheKey = 'api_artists_index_genre_' . $genreId;

        $artists = Cache::remember($cacheKey, now()->addMinutes(60), function () {
            return app(Pipeline::class)
                ->send(self::query())
                ->through([
                    ArtistsSelectFilter::class,
                    ArtistsVisibleFilter::class,
                    ArtistsGetByGenreFilter::class,
                    ArtistsWithGenreFilter::class
                ])
                ->thenReturn()
                ->get();
        });

        return ArtistIndexDTO::fromCollection($artists);
    }

    /**
     * Searches for artists by name with caching for optimized performance.
     *
     * This method retrieves up to three visible artists whose names match the given
     * search string. It uses a pipeline with filters for search, visibility, and selection.
     * Results are cached for 10 minutes to reduce database load and improve response times.
     *
     * @param string $searchString The search query for artist names.
     * @return array An array of artist data transformed by ArtistSearchDTO, including ID, name, and avatar URL.
     */
    public static function apiSearch($searchString): array
    {
        $cacheKey = 'api_artists_search_' . $searchString;

        $artists = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($searchString) {
            return app(Pipeline::class)
                ->send(self::query())
                ->through([
                    new ArtistsSearchFilter($searchString),
                    ArtistsVisibleFilter::class,
                    ArtistsSearchSelectFilter::class
                ])
                ->thenReturn()
                ->limit(3)
                ->get();
        });

        return ArtistSearchDTO::fromCollection($artists);
    }

    public function genres()
    {
        return $this->hasManyThrough(
            Genre::class,
            YouTubeVideo::class,
            'artist_id',
            'id',
            'id',
            'genre_id'
        );
    }

    public function youTubeVideos()
    {
        return $this->hasMany(
            YouTubeVideo::class,
            'artist_id',
            'id'
        );
    }
}
