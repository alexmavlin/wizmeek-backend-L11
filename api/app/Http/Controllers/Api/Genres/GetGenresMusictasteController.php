<?php

namespace App\Http\Controllers\Api\Genres;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GetGenresMusictasteController extends Controller
{
    public function __invoke()
    {
        $genres = Genre::getUsersTaste();

        return response()->json($genres);
    }
}
