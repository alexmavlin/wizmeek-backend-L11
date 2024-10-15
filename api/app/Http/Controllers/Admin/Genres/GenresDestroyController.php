<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenresDestroyController extends Controller
{
    public function __invoke(Genre $genre)
    {
        dd($genre);
    }
}
