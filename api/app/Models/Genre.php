<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public static function get($filterExpression = '')
    {
        // Start the query
        $query = self::query();

        // Apply filter if $filterExpression is not empty
        if (!empty($filterExpression)) {
            $query->where('genre', 'like', '%' . $filterExpression . '%');
        }

        // Paginate the results with 10 records per page
        return $query->paginate(10);
    }

    public static function deleteGenre($id)
    {
        $query = self::query();

        // Find the genre by ID and eager load related YouTube videos
        $genre = $query->with([
            'youTubeVideos' => function ($q) {
                $q->select('id', 'genre_id');
            }
        ])->find($id);

        if (!$genre) {
            throw new Exception('The specified genre was not found.');
        }

        // dd($genre);

        // Delete associated YouTube videos (soft delete if `SoftDeletes` is used)
        foreach ($genre->youTubeVideos as $video) {
            $video->delete();
        }

        // Delete the genre itself
        return $genre->delete();
    }

    public static function getForSelect()
    {
        $query = self::query();

        $query->select('id', 'genre');
        return $query->get();
    }

    public function youTubeVideos()
    {
        return $this->hasMany(YouTubeVideo::class, 'genre_id', 'id');
    }
}
