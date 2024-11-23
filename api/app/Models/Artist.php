<?php

namespace App\Models;

use App\Traits\GenreTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use HasFactory, SoftDeletes, GenreTrait;

    protected $table = "artists";
    protected $guarded = [];

    /**
     * Get paginated artists with optional filtering.
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
            $query->where('name', 'like', '%' . $filterExpression . '%');
        }

        // Paginate the results with 10 records per page
        return $query->paginate(10);
    }

    public static function getForApi() {
        $query = self::query();
        $query->select('id', 'name', 'avatar', 'short_description');
        $query->with([
            "genres" => function($query) {
                $query->select('genre');
            }
        ]);
        $artists = $query->get();

        $data = [];

        foreach ($artists as $artist) {
            $data[] = [
                '_id' => $artist->id,
                'nFan' => 250,
                'shareLink' => "https://example.com/profile/1",
                'cover' => asset($artist->avatar),
                'name' => $artist->name,
                'bio' => $artist->short_description,
                'type' => self::mergeGenreNames($artist->genres)
            ];
        }
        return $data;
    }

    public function genres()
    {
        return $this->hasManyThrough(
            Genre::class,        // Final target model
            YouTubeVideo::class, // Intermediate model
            'artist_id',         // Foreign key on YouTubeVideo (linking to Artist)
            'id',                // Foreign key on Genre (linking to YouTubeVideo)
            'id',                // Local key on Artist
            'genre_id'           // Local key on YouTubeVideo
        );
    }
}

/* 
{
        "_id": 1,
        "nFan": 250,
        "shareLink": "https://example.com/profile/1",
        "cover": "/img/oble_reed/avatar_1.webp",
        "name": "Jane Doe",
        "bio": "A passionate artist and traveler. Sharing my journey through art and photography.",
        "type": "R&B"
    },
*/