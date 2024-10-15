<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Genre;

class YouTubeVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'you_tube_videos';
    protected $guarded = [];

    public static function getVideosForIndex() {
        $query = self::query();

        // Fetch input values from the request
        $title = request('title');
        $genre = request('genre');
        $country = request('country');
        $flag = request('flags');
        /* $contentType = request('content_type'); */

        // Apply filters conditionally
        $query->when($title, function($q, $title) {
            return $q->where('title', 'like', '%' . $title . '%');
        });

        $query->when($genre, function($q, $genre) {
            return $q->where('genre_id', $genre);
        });

        $query->when($country, function($q, $country) {
            return $q->where('country_id', $country);
        });

        $query->when($flag, function ($q, $flag) {
            if ($flag === 'new') {
                return $q->where('new', 1);
            } elseif ($flag === 'editors_pick') {
                return $q->where('editors_pick', 1);
            } elseif ($flag === 'throwback') {
                return $q->where('throwback', 1);
            }
        });

        /* $query->when($contentType, function($q, $contentType) {
            return $q->where('content_type_id', $contentType);
        }); */
        $query->with([
            'artist:id,name',
            'genre:id,genre',
            'country:id,flag,name'
        ]);

        $query->select(
            'id', 
            'content_type_id', 
            'artist_id',
            'title',
            'thumbnail',
            'release_date',
            'genre_id',
            'country_id',
            'editors_pick',
            'new',
            'throwback'
        );

        // Return paginated results ordered by creation date
        return $query->orderBy('created_at', 'DESC')->paginate(10);
    }

    public function artist() {
        return $this->belongsTo(Artist::class, 'artist_id', 'id');
    }

    public function genre() {
        return $this->belongsTo(Genre::class, 'genre_id', 'id');
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
