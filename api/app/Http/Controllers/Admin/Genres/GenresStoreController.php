<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Genres\GenresCreateRequest;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenresStoreController extends Controller
{
    public function __invoke(GenresCreateRequest $request)
    {
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                $filename = uniqid('genre_') . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('genres'), $filename);

                $imagePath = 'genres/' . $filename;
            }

            $genre = Genre::create([
                'genre' => $request->input('genre'),
                'color' => $request->input('color'),
                'img_link' => $imagePath,
            ]);
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occurred while trying to store a genre. Error: ' . $error->getMessage());
        }

        return redirect()->route('admin_genres_index')->with('success', 'Genre created successfully.');
    }
}
