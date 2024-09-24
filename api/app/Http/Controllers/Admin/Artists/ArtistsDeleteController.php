<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistsDeleteController extends Controller
{
    public function __invoke(Artist $artist)
    {
        // Soft delete the artist
        $artist->delete();

        // Redirect back with a success message
        return redirect()->route('admin_artists_index')->with('success', "Artist $artist->name deleted successfully.");
    }
}