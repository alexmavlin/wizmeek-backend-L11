<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Models\Genre;

class GenresEditController extends Controller
{
    public function __invoke(Genre $genre)
    {
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_edit.scss'
            ],
            "js" => [

            ],
            "genre" => $genre
        ];
        return view('admin.genres.genresedit', compact('data'));
    }
}
