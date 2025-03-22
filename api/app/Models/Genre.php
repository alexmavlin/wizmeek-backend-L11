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

    public static function getUsersTaste()
    {

        $query = self::query();
        $query->select('id', 'genre', 'color');
        $genres = $query->get()->toArray();

        // dd($genres);

        $user = Auth::user();

        $user->load([
            'genreTaste' => function ($q) {
                $q->select('genres.id');
            }
        ]);

        $userTastyGenres = $user->genreTaste->pluck('id')->toArray();

        $ret = [];
        foreach ($genres as $genre) {
            $ret[] = [
                'id' => $genre['id'],
                'genre' => $genre['genre'],
                'color' => $genre['color'],
                'isGenreTasty' => in_array($genre['id'], $userTastyGenres) ? true : false
            ];
        }
        return $ret;
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

    public static function getForApi()
    {
        $query = self::query();
        $query->select('id', 'genre', 'color');

        $genres = $query->get();

        $response = [];

        foreach ($genres as $genre) {
            $response[] = [
                'id' => $genre->id,
                'label' => $genre->genre,
                'color' => $genre->color
            ];
        }

        return $response;
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
