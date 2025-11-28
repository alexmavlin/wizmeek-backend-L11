<?php

namespace App\Http\Controllers\Api\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Support\Facades\Auth;

class GetArtistsListController extends Controller
{
    public function __invoke()
    {
        // return response()->json(Auth::user());
        // dd(Auth::user()->id);
        $artists = Artist::getForApi(Auth::user());

        // dd($artists);

        return response()->json($artists);
    }
}
