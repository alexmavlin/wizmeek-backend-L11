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
            $artist = Genre::create([
                'genre' => $request->input('genre'),
                'color' => $request->input('color')
            ]);
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to store a genre. Error: ' . $error->getMessage());
        }
    
        return redirect()->route('admin_genres_index')->with('success', 'Genre created successfully.');
    }
}
