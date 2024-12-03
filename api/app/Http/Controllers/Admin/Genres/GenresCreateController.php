<?php

namespace App\Http\Controllers\Admin\Genres;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GenresCreateController extends Controller
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
        return view('admin.genres.genrescreate', compact('data'));
    }
}
