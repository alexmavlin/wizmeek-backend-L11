<?php

namespace App\Models;

use App\DataTransferObjects\Api\ArtistsDTO\ArtistIndexDTO;
use App\DataTransferObjects\Api\ArtistsDTO\ArtistSearchDTO;
use App\QueryFilters\Admin\Artists\ArtistEditSelectFilter;
use App\QueryFilters\Admin\Artists\ArtistsLoadRelationsFilter;
use App\QueryFilters\Admin\Artists\GetFilteredSelectFilter;
use App\QueryFilters\Admin\Artists\WithYouTubeVideosFilter;
use App\QueryFilters\Api\Artists\ArtistsGetByGenreFilter;
use App\QueryFilters\Api\Artists\ArtistsSearchFilter;
use App\QueryFilters\Api\Artists\ArtistsSearchSelectFilter;
use App\QueryFilters\Api\Artists\ArtistsSelectFilter;
use App\QueryFilters\Api\Artists\ArtistsVisibleFilter;
use App\QueryFilters\Api\Artists\ArtistsWithCountriesFilter;
use App\QueryFilters\Api\Artists\ArtistsWithGenreFilter;
use App\QueryFilters\CommonFindFilter;
use App\QueryFilters\CommonPaginatorFilter;
use App\QueryFilters\CommonSearchFilter;
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
     * Get paginated artists with optional filtering via pipeline.
     *
     * This method applies a sequence of filters to the Artist query using Laravel's Pipeline:
     * - CommonSearchFilter: applies a basic search filter on the 'name' field.
     * - GetFilteredSelectFilter: applies additional filtering logic (e.g., by selected criteria).
     * - CommonPaginatorFilter: paginates the result with a limit of 10 per page.
     *
     * @param string $filterExpression The search keyword to filter artist names.
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated collection of filtered artists.
     */
    public static function getFiltered($filterExpression = ''): LengthAwarePaginator
    {
        $artists = app(Pipeline::class)
            ->send(self::query())
            ->through([
                new CommonSearchFilter($filterExpression, 'name'),
                GetFilteredSelectFilter::class,
                new CommonPaginatorFilter(10),
            ])
            ->thenReturn();

        return $artists;
    }

    /**
     * Deletes an artist and their associated YouTube videos using a pipeline.
     *
     * This method retrieves the artist by ID through a pipeline that applies:
     * - WithYouTubeVideosFilter: eager loads the artist's YouTube videos.
     * - CommonFindFilter: finds the artist by the given ID.
     *
     * If the artist exists, it deletes the artist. The associated YouTube videos are expected
     * to be deleted automatically via a `onDelete('cascade')` constraint on the relationship.
     * If the artist is not found, an exception is thrown.
     *
     * @param int $id The ID of the artist to be deleted.
     * @throws \Exception If the artist is not found.
     * @return bool|null Returns `true` if the deletion was successful, `false` if it failed, or `null` if no deletion occurred.
     */

    public static function deleteArtist($id)
    {
        $artist = app(Pipeline::class)
            ->send(self::query())
            ->through([
                WithYouTubeVideosFilter::class,
                new CommonFindFilter($id)
            ])
            ->thenReturn();

        if (!$artist) {
            throw new Exception('The specified artist was not found.');
        }

        // $artist->youTubeVideos()->delete();
        // Check later if it has onDelete('cascade') in a relationship

        return $artist->delete();
    }

    /**
     * Retrieves an artist for admin editing using a pipeline.
     *
     * This method applies the following filters in sequence via Laravel's Pipeline:
     * - ArtistEditSelectFilter: selects specific fields required for editing.
     * - ArtistsLoadRelationsFilter: eager loads related data necessary for admin view/edit.
     * - CommonFindFilter: retrieves the artist by the provided ID.
     *
     * @param int $id The ID of the artist to retrieve.
     * @throws \Exception If the artist is not found (handled inside CommonFindFilter).
     * @return \App\Models\Artist|null The artist model with selected fields and relations loaded, or null if not found.
     */

    public static function getForAdminEdit($id)
    {
        $artist = app(Pipeline::class)
            ->send(self::query())
            ->through([
                ArtistEditSelectFilter::class,
                ArtistsLoadRelationsFilter::class,
                new CommonFindFilter($id)
            ])
            ->thenReturn();
        $query = self::query();

        return $artist;
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
                    ArtistsWithGenreFilter::class,
                    ArtistsWithCountriesFilter::class
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

    /* public function genres()
    {
        return $this->hasManyThrough(
            Genre::class,
            YouTubeVideo::class,
            'artist_id',
            'id',
            'id',
            'genre_id'
        );
    } */

    public function youTubeVideos()
    {
        return $this->hasMany(
            YouTubeVideo::class,
            'artist_id',
            'id'
        );
    }

    public function genres()
    {
        return $this->belongsToMany(
            Genre::class,
            'artists_genres',
            'artist_id',
            'genre_id'
        );
    }

    public function countries()
    {
        return $this->belongsToMany(
            Country::class,
            'artists_countries',
            'artist_id',
            'country_id'
        );
    }
}
