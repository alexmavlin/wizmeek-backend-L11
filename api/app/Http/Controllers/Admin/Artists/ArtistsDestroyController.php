<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistsDestroyController extends Controller
{
    public function __invoke(Artist $artist)
    {
        dd($artist);
    }
}
