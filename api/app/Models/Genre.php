<?php

namespace App\Models;

use App\DataTransferObjects\Api\GenresDTO\GenreIndexDTO;
use App\DataTransferObjects\Api\GenresDTO\GenreTasteDTO;
use App\QueryFilters\Admin\Genres\GetFilteredSelectFilter;
use App\QueryFilters\Api\Genres\AddGenreTastyFlag;
use App\QueryFilters\Api\Genres\GenreSelectFilter;
use App\QueryFilters\CommonPaginatorFilter;
use App\QueryFilters\CommonSearchFilter;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;

class Genre extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "genres";
    protected $guarded = false;

    /**
     * Get paginated genres with optional filtering using a pipeline.
     *
     * This method builds a query for genres and passes it through a Laravel Pipeline with the following filters:
     * - CommonSearchFilter: filters genres by a search term on the 'genre' field.
     * - GetFilteredSelectFilter: applies additional selection logic (e.g., selected fields or ordering).
     * - CommonPaginatorFilter: paginates the results with a default limit of 10 items per page.
     *
     * @param string $filterExpression Optional search string to filter genres by name.
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated and filtered collection of genres.
     */

    public static function getFiltered($filterExpression = '')
    {
        $genres = app(Pipeline::class)
            ->send(self::query())
            ->through([
                new CommonSearchFilter($filterExpression, 'genre'),
                GetFilteredSelectFilter::class,
                new CommonPaginatorFilter(10)
            ])
            ->thenReturn();

        return $genres;
    }

    /**
     * Retrieves a list of all genres for selection purposes.
     *
     * This method fetches all records containing their ID and genre name, 
     * which can be used in dropdowns or selection lists.
     *
     * @return \Illuminate\Database\Eloquent\Collection The collection of genres containing ID and genre name.
     */
    public static function getForSelect()
    {
        $query = self::query();

        $query->select('id', 'genre');
        return $query->get();
    }

    /**
     * Deletes a genre and its associated YouTube videos.
     *
     * This method retrieves the genre by ID along with its related YouTube videos.
     * If the genre exists, all associated YouTube videos are deleted before deleting the genre.
     * If the genre is not found, an exception is thrown.
     *
     * @param int $id The ID of the genre to be deleted.
     * @throws \Exception If the genre is not found.
     * @return bool|null Returns `true` if the deletion was successful, `false` if it failed, or `null` if no deletion occurred.
     */
    public static function deleteGenre($id)
    {
        $query = self::query();

        $genre = $query->with([
            'youTubeVideos' => function ($q) {
                $q->select('id', 'genre_id');
            }
        ])->find($id);

        if (!$genre) {
            throw new Exception('The specified genre was not found.');
        }
        foreach ($genre->youTubeVideos as $video) {
            $video->delete();
        }

        return $genre->delete();
    }

    /**
     * Retrieves the user's taste preferences for music genres.
     *
     * This method uses a pipeline to filter and enrich the list of available genres.
     * It selects essential genre attributes and determines whether each genre is
     * part of the authenticated user's preferred genres. The result is transformed
     * into a standardized DTO format for API consumption.
     *
     * Pipeline filters used:
     * - GenreSelectFilter: selects relevant fields from the genres table.
     * - AddGenreTastyFlag: adds a boolean flag indicating user's preference.
     *
     * @return array An array of genres with ID, name, color, image, and a 
     *               'isGenreTasty' flag indicating user preference.
     */
    public static function getUsersTaste(): array
    {
        $genres = app(Pipeline::class)
            ->send(self::query())
            ->through([
                GenreSelectFilter::class,
                AddGenreTastyFlag::class
            ])
            ->thenReturn();

        return GenreTasteDTO::fromCollection($genres);
    }

    /**
     * Retrieves a list of music genres formatted for API response.
     *
     * This method uses a pipeline to filter the genres query, selecting only the
     * necessary fields required for the API. The filtered data is then transformed
     * into a standardized DTO (Data Transfer Object) format for consistent frontend consumption.
     *
     * Pipeline filters used:
     * - GenreSelectFilter: selects essential genre fields (e.g., id, name, color, image).
     *
     * @return array An array of genres, each containing attributes such as ID, name, color, and image URL.
     */
    public static function getForApi(): array
    {
        $genres = app(Pipeline::class)
            ->send(self::query())
            ->through([
                GenreSelectFilter::class
            ])
            ->thenReturn()
            ->get();

        return GenreIndexDTO::fromCollection($genres);
    }

    public function youTubeVideos()
    {
        return $this->hasMany(
            YouTubeVideo::class,
            'genre_id',
            'id'
        );
    }

    public function tasteUsers()
    {
        return $this->belongsToMany(
            User::class,
            'users_genres_taste',
            'genre_id',
            'user_id'
        );
    }

    public function artists()
    {
        return $this->belongsToMany(
            Artist::class,
            'artists_genres',
            'genre_id',
            'artist_id'
        );
    }
}
