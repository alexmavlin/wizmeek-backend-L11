<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Genre;
use Illuminate\Http\Request;

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
            'release_date',
            'thumbnail',
            'genre_id',
            'country_id',
            'editors_pick',
            'new',
            'throwback'
        );

        // Return paginated results ordered by creation date
        return $query->orderBy('created_at', 'DESC')->paginate(10);
    }

    public function removeRelatedSelections()
    {
        $this->load(['landingItems', 'highlightItems']);

        foreach ($this->landingItems as $landingItem) {
            $landingItem->delete();
        }

        foreach ($this->highlightItems as $highlightItem) {
            $highlightItem->delete();
        }
    }

    public static function getVideosApi($request) {
        $limit = $request->header('X-Limit', 10);
        $mode = $request->header('X-Mode', 'latest');
        $genre = $request->header('X-Genre');

        $query = self::query();

        if ($genre) {
            $query->whereHas('genre', function ($q) use ($genre) {
                $q->where('genre', 'like', '%' . $genre . '%'); // assuming 'name' is the column in genres table
            });
        }

        switch ($mode) {
            case 'random':
                $query->inRandomOrder();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $query->select(
            'id', 
            'country_id',
            'genre_id',
            'artist_id',
            'youtube_id',
            'thumbnail',
            'editors_pick',
            'new',
            'throwback',
            'title', 
            'release_date'
        );

        $query->with([
            'country' => function ($q) {
                $q->select('id', 'flag');
            },
            'genre' => function ($q) {
                $q->select('id', 'genre');
            },
            'artist' => function ($q) {
                $q->select('id', 'name');
            }
        ]);

        // Limit the number of videos
        $videos = $query->limit($limit)->get();

        $response = [];
        // dd($videos);
        foreach ($videos as $video) {
            $response[] = [
                'artist' => $video->artist->name,
                'title' => $video->title,
                'youtube_id' => $video->youtube_id,
                'thumbnail' => $video->thumbnail,
                'release_year' => date('Y', strtotime($video->release_date)),
                'genre' => $video->genre ? $video->genre->genre : "NaN",
                'country_flag' => asset($video->country->flag),
                'editors_pick' => $video->editors_pick ? true : false,
                'new' => $video->new ? true : false,
                'throwback' => $video->throwback ? true : false
            ];
        }

        return $response;
    }

    public static function getForLoader($searchString) {
        $query = self::query();
        //dd($searchString);
        $query->select('id', 'title', 'thumbnail', 'artist_id');
        // dd($query->get());
        $query->where('title', 'like', '%' . $searchString . '%');
        $query->orWhereHas('artist', function ($q) use ($searchString) {
            $q->where('name', 'like', '%' . $searchString . '%');
        });
        $query->with([
            'artist' => function($q) {
                $q->select('id', 'name');
            }
        ]);
        $query->orderBy('id', 'ASC');

        $videos = $query->get();

        $data = [];

        foreach ($videos as $video) {
            $data[] = [
                'id' => $video->id,
                'artist' => $video->artist->name,
                'title' => $video->title,
                'thumbnail' => $video->thumbnail
            ];
        }
        return $data;
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

    public function landingItems() {
        return $this->hasMany(LandingPageVideo::class, 'video_id');
    }

    public function highlightItems() {
        return $this->hasMany(HiglighVideo::class, 'video_id');
    }
}
