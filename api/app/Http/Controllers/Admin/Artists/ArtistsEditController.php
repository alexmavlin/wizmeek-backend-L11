<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Country;
use App\Models\Genre;
use Exception;
use Illuminate\Support\Facades\Log;

class ArtistsEditController extends Controller
{
    public function __invoke($artist)
    {
        try {
            $data = [
                "scss" => [
                    'resources/scss/admin/artists/artists_edit.scss'
                ],
                "js" => [
                    'resources/js/admin/forms.js'
                ],
                "artist" => Artist::getForAdminEdit($artist),
                "genres" => Genre::getForSelect(),
                "countries" => Country::getForSelect()
            ];
            // dd($data);
            return view('admin.artists.artistsedit', compact('data'));
        } catch (Exception $error) {
            $message = 'An error has occured during an attempt to load data while accessing ' . route('admin_artists_edit') . '.<br><br>Error: ' . $error->getMessage();
            Log::error($message);
            return redirect()->back()->with('error', $message . '<br><br>The error has been logged.');
        }
    }
}
