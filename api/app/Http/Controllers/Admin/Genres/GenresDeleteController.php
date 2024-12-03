<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Models\Genre;

class GenresDeleteController extends Controller
{
    public function __invoke($genre)
    {
        try {
            Genre::deleteGenre($genre);
            return redirect()->route('admin_genres_index')->with('success', "Artist $genre->genre deleted successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to delete the genre. Error: ' . $e->getMessage());
        }
    }
}
