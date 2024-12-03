<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Genres\GenresUpdateRequest;
use App\Models\Genre;

class GenresUpdateController extends Controller
{
    public function __invoke(GenresUpdateRequest $request, Genre $genre)
    {
        // dd($request->all());
        // Update other artist data
        $genre->genre = $request->input('genre');
        $genre->color = $request->input('color');

        // Save the artist record
        $genre->save();

        // Redirect or return a success response
        return redirect()->route('admin_genres_view', $genre)->with('success', 'Genre updated successfully.');
    }
}
