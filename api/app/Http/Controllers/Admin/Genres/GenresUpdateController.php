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

            if ($request->hasFile('image')) {
                if ($genre->img_link && file_exists(public_path($genre->img_link))) {
                    unlink(public_path($genre->img_link));
                }

                $image = $request->file('image');
                $filename = uniqid('genre_') . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('genres'), $filename);

                $genre->img_link = 'genres/' . $filename;
            }

            $genre->save();
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occurred during an attempt to update a genre. Error: ' . $error->getMessage());
        }

        return redirect()->route('admin_genres_view', $genre)->with('success', 'Genre updated successfully.');
    }
}
