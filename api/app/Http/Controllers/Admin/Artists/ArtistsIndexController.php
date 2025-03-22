<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Artists\ArtistsSearchFilterRequest;
use App\Models\Artist;

class ArtistsIndexController extends Controller
{
    public function __invoke(ArtistsSearchFilterRequest $request) 
    {
        $filterExpression = $request->input('filter_expression', '');

        try {
            $artists = Artist::getFiltered($filterExpression);
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to access artists list. Error: ' . $error->getMessage());
        }
        
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [

            ],
            "artists" => $artists,
        ];
        return view('admin.artists.artistsindex', compact('data'));
    }
}
