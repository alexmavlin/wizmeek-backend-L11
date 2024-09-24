<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistsIndexController extends Controller
{
    public function __invoke(Request $request) 
    {
        $filterExpression = $request->input('filter_expression', '');
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [

            ],
            "artists" => Artist::get($filterExpression),
        ];
        return view('admin.artists.artistsindex', compact('data'));
    }
}
