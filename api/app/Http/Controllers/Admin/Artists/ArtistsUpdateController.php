<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Artists\ArtistsUpdateRequest;
use App\Models\Artist;
use Illuminate\Support\Facades\File;

class ArtistsUpdateController extends Controller
{
    public function __invoke(ArtistsUpdateRequest $request, Artist $artist)
    {
        // Check if avatar is provided in the request
        if ($request->hasFile('avatar')) {

            // Check if the artist already has an avatar
            if ($artist->avatar && File::exists(public_path($artist->avatar))) {
                // Delete the existing avatar file
                File::delete(public_path($artist->avatar));
            }

            // Upload the new avatar file
            $avatar = $request->file('avatar');
            
            // Generate a unique file name
            $fileName = uniqid() . '.' . $avatar->getClientOriginalExtension();
            
            // Define the upload path (public/img/artists/avatars)
            $destinationPath = public_path('img/artists/avatars');
            
            // Move the file to the specified path
            $avatar->move($destinationPath, $fileName);
            
            // Get the file path for saving into the database
            $filePath = 'img/artists/avatars/' . $fileName;

            // Update the artist's avatar in the database
            $artist->avatar = $filePath;
        }

        // Update other artist data
        $artist->name = $request->input('name');
        $artist->short_description = $request->input('short_description');
        $artist->full_description = $request->input('full_description');
        $artist->is_visible = $request->input('is_visible') ? 1 : 0;

        // Save the artist record
        $artist->save();

        // Redirect or return a success response
        return redirect()->route('admin_artists_view', $artist)->with('success', 'Artist updated successfully.');
    }
}
