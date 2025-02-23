<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class GetArtistsListController extends Controller
{
    public function __invoke()
    {
        $artists = Artist::getForApi();

        return response()->json($artists);
    }
}
