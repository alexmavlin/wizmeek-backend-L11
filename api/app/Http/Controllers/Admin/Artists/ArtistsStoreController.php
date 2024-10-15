<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Artists\ArtistsStoreRequest;
use App\Models\Artist;

class ArtistsStoreController extends Controller
{
    public function __invoke(ArtistsStoreRequest $request)
    {
        // Handle the file upload for the avatar
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            
            // Generate a unique file name
            $fileName = uniqid() . '.' . $avatar->getClientOriginalExtension();
            
            // Define the upload path (public/img/artists/avatars)
            $destinationPath = public_path('img/artists/avatars');
            
            // Move the file to the specified path
            $avatar->move($destinationPath, $fileName);
            
            // Get the file path for saving into the database
            $filePath = 'img/artists/avatars/' . $fileName;
        }
    
        // Now you can save the artist with the avatar path
        $artist = Artist::create([
            'name' => $request->input('name'),
            'avatar' => $filePath, // Save the file path in the database
            'short_description' => $request->input('short_description'),
            'full_description' => $request->input('full_description')
        ]);
    
        // Redirect or return success response
        return redirect()->route('admin_artists_index')->with('success', 'Artist created successfully.');
    }

}
