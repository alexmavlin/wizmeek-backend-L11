<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Genre extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "genres";
    protected $guarded = false;

    /**
     * Get paginated genres with optional filtering.
     *
     * @param string $filterExpression
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function getFiltered($filterExpression = '')
    {
        $query = self::query();

        if (!empty($filterExpression)) {
            $query->where('genre', 'like', '%' . $filterExpression . '%');
        }

        $query->select('id', 'genre');

        return $query->paginate(10);
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
     * This method fetches all available genres and checks whether each genre 
     * is part of the authenticated user's preferred genres. The result includes 
     * the genre's ID, name, color, and whether the user likes it.
     *
     * @return array An array of genres with user preference information.
     */
    public static function getUsersTaste()
    {
        $query = self::query();
        $query->select('id', 'genre', 'color', 'img_link');
        $genres = $query->get();

        $user = Auth::user();

        $user->load([
            'genreTaste' => function ($q) {
                $q->select('genres.id');
            }
        ]);

        $userTastyGenres = $user->genreTaste->pluck('id')->toArray();

        return $genres->map(function ($genre) use ($userTastyGenres) {
            return [
                'id' => $genre->id,
                'genre' => $genre->genre,
                'color' => $genre->color,
                'image' => asset($genre->img_link),
                'isGenreTasty' => in_array($genre->id, $userTastyGenres)
            ];
        })->toArray();
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
     * Retrieve genres for API response.
     *
     * @return array The list of genres formatted for API response.
     */
    public static function getForApi(): array
    {
        $query = self::query();
        $query->select('id', 'genre', 'color', 'img_link');

        return $query->get()->map(function ($genre) {
            return [
                'id' => $genre->id,
                'label' => $genre->genre,
                'color' => $genre->color,
                'image' => asset($genre->img_link)
            ];
        })->toArray();
    }

    public function youTubeVideos()
    {
        return $this->hasMany(YouTubeVideo::class, 'genre_id', 'id');
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
}
