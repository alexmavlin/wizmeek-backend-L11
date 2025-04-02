<?php

namespace App\Models;

use App\QueryFilters\Api\Artists\ArtistsGetByGenreFilter;
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
     * Retrieves a list of visible artists formatted for API response.
     *
     * This method fetches artists from the database who are marked as visible (`is_visible = 1`),
     * along with their genres. It then formats the data into an array suitable for API responses.
     *
     * @return array The list of artists with formatted attributes including ID, name, avatar, short description, and genres.
     */
    public static function getForApi(): array
    {
        $genreId = (string) request()->header('X-Genre') ?: (string) '0';
        $cacheKey = 'artists_for_api_by_genre' . $genreId;

        $artists = Cache::remember($cacheKey, now()->addMinutes(20), function () {
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

        return self::getApiArtistsIndexDatatype($artists);
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
     * Searches for artists by name with caching for optimized performance.
     *
     * This method retrieves artists whose names match the given search string,
     * limiting the results to three. The results are cached for 10 minutes (600 seconds)
     * to reduce database load.
     *
     * @param string $searchString The search query for artist names.
     * @return \Illuminate\Support\Collection A collection of artists with their ID, name, and avatar URL.
     */
    public static function apiSearch($searchString)
    {
        $query = Cache::remember("apiArtistSearch:$searchString", 600, function () use ($searchString) {
            return self::where('name', 'like', '%' . $searchString . '%')
                ->where('is_visible', 1)
                ->select('id', 'name', 'avatar', 'is_visible')
                ->limit(3)
                ->get();
        });

        $response = $query->map(function ($artist) {
            return [
                'id' => $artist->id,
                'name' => $artist->name,
                'avatar' => asset($artist->avatar),
            ];
        });

        return $response;
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
