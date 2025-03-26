<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Artists\ArtistsUpdateRequest;
use App\Models\Artist;
use App\Traits\FileManipulatorTrait;
use Illuminate\Support\Facades\File;

class ArtistsUpdateController extends Controller
{
    use FileManipulatorTrait;

    public function __invoke(ArtistsUpdateRequest $request, Artist $artist)
    {
        try {
            if ($request->hasFile('avatar')) {
                $filePath = $this->updateArtistAvatar($artist, $request->file('avatar'));
                $artist->avatar = $filePath;
            }

            $artist->name = $request->input('name');
            $artist->short_description = $request->input('short_description');
            $artist->full_description = $request->input('full_description');
            $artist->is_visible = $request->input('is_visible') ? 1 : 0;
            $artist->spotify_link = $request->input('spotify_link', '');
            $artist->apple_music_link = $request->input('apple_music_link', '');
            $artist->instagram_link = $request->input('instagram_link', '');
    
            $artist->save();
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to update an artist. Error: ' . $error->getMessage());
        }

        return redirect()->route('admin_artists_view', $artist)->with('success', 'Artist updated successfully.');
    }
}
