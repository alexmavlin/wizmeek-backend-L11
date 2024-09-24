<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenresIndexController extends Controller
{
    public function __invoke(Request $request) 
    {
        $filterExpression = $request->input('filter_expression', '');
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [

            ],
            "genres" => Genre::get($filterExpression),
        ];
        return view('admin.genres.genresindex', compact('data'));
    }
}
