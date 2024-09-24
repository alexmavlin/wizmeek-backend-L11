<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenresViewController extends Controller
{
    public function __invoke(Genre $genre)
    {
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_view.scss'
            ],
            "js" => [

            ],
            "genre" => $genre,
        ];
        return view('admin.genres.genresview', compact('data'));
    }
}
