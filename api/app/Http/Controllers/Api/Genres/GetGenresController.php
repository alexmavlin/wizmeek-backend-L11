<?php

namespace App\Http\Controllers\Api\Genres;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GetGenresController extends Controller
{
    public function __invoke()
    {
        $genres = Genre::getForApi();

        return response()->json($genres);
    }
}
