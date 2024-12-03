<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;

class ArtistsEditController extends Controller
{
    public function __invoke(Artist $artist)
    {
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_edit.scss'
            ],
            "js" => [

            ],
            "artist" => $artist
        ];
        return view('admin.artists.artistsedit', compact('data'));
    }
}
