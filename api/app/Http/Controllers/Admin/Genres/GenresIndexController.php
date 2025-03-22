<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Genres\GenresSearchFilterRequest;
use App\Models\Genre;

class GenresIndexController extends Controller
{
    public function __invoke(GenresSearchFilterRequest $request) 
    {
        $filterExpression = $request->input('filter_expression', '');

        try {
            $genres = Genre::getFiltered($filterExpression);
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to access genres list. Error: ' . $error->getMessage());
        }

        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [

            ],
            "genres" => $genres,
        ];
        return view('admin.genres.genresindex', compact('data'));
    }
}
