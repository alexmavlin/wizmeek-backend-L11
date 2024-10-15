<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Models\Genre;

class GenresDeleteController extends Controller
{
    public function __invoke(Genre $genre)
    {
        // Soft delete the artist
        $genre->delete();

        // Redirect back with a success message
        return redirect()->route('admin_genres_index')->with('success', "Artist $genre->genre deleted successfully.");
    }
}
