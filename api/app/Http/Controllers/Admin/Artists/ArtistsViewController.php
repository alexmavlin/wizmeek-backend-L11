<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistsViewController extends Controller
{
    public function __invoke(Artist $artist)
    {
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_view.scss'
            ],
            "js" => [

            ],
            "artist" => $artist,
        ];
        return view('admin.artists.artistsview', compact('data'));
    }
}
