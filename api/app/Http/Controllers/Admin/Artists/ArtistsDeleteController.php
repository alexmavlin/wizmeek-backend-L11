<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistsDeleteController extends Controller
{
    public function __invoke($artist)
    {
        try {
            Artist::deleteArtist($artist);
            return redirect()->route('admin_artists_index')->with('success', "Artist $artist->name deleted successfully.");
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to delete artist. Error: ' . $error->getMessage());
        }
    }
}