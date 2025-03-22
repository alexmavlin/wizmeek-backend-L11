<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Genres\GenresUpdateRequest;
use App\Models\Genre;

class GenresUpdateController extends Controller
{
    public function __invoke(GenresUpdateRequest $request, Genre $genre)
    {
        try {
            $genre->genre = $request->input('genre');
            $genre->color = $request->input('color');
            $genre->save();
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to update a genre. Error: ' . $error->getMessage());
        }

        return redirect()->route('admin_genres_view', $genre)->with('success', 'Genre updated successfully.');
    }
}
