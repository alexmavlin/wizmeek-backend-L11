<?php

namespace App\Http\Controllers\Admin\Artists;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Artists\ArtistsStoreRequest;
use App\Models\Artist;
use App\Traits\FileManipulatorTrait;
use Illuminate\Support\Facades\Log;

class ArtistsStoreController extends Controller
{
    use FileManipulatorTrait;

    public function __invoke(ArtistsStoreRequest $request)
    {
        // dd($request);
        try {
            if ($request->hasFile('avatar')) {
                $avatarPath = $this->uploadArtistAvatar($request->file('avatar'));
            }

            $artist = Artist::create([
                'name' => $request->input('name'),
                'avatar' => $avatarPath,
                'short_description' => $request->input('short_description') ?? '',
                'full_description' => $request->input('full_description') ?? '',
                'is_visible' => $request->input('is_visible') ? 1 : 0 ?? '',
                'spotify_link' => $request->input('spotify_link', '') ?? '',
                'apple_music_link' => $request->input('apple_music_link', '') ?? '',
                'instagram_link' => $request->input('instagram_link') ?? ''
            ]);

            $artist->genres()->attach($request->genres);
            $artist->countries()->attach($request->countries);
        } catch (\Exception $error) {
            $message = 'An error has occured during an attempt to store an artist. Error: ' . $error->getMessage();
            Log::error($message);
            return redirect()->back()
                    ->with('error', $message)
                    ->withInput();
        }
    
        return redirect()->route('admin_artists_index')->with('success', 'Artist created successfully.');
    }

}
