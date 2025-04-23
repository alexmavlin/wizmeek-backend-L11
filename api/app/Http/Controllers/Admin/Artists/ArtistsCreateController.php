<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Genre;
use Exception;
use Illuminate\Support\Facades\Log;

class ArtistsCreateController extends Controller
{
    public function __invoke()
    {
        try {
            $data = [
                "scss" => [
                    'resources/scss/admin/artists/artists_create.scss'
                ],
                "js" => [
                    'resources/js/admin/forms.js'
                ],
                "genres" => Genre::getForSelect(),
                "countries" => Country::getForSelect()
            ];

            return view('admin.artists.artistscreate', compact('data'));
        } catch (Exception $error) {
            $message = 'An error has occured during an attempt to load data while accessing ' . route('admin_artists_create') . '.<br><br>Error: ' . $error->getMessage();
            Log::error($message);
            return redirect()->back()->with('error', $message . '<br><br>The error has been logged.');
        }
    }
}
