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
        // Now you can save the artist with the avatar path
        $artist = Genre::create([
            'genre' => $request->input('genre'),
            'color' => $request->input('color')
        ]);
    
        // Redirect or return success response
        return redirect()->route('admin_genres_index')->with('success', 'Genre created successfully.');
    }
}
