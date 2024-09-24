<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArtistsCreateController extends Controller
{
    public function __invoke()
    {
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_create.scss'
            ],
            "js" => [

            ]
        ];
        return view('admin.artists.artistscreate', compact('data'));
    }
}
