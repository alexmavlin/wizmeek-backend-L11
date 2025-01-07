<?php

namespace App\Http\Controllers\Api\Genres;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Genres\UnbindGenreTasteRequest;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UnbindGenreTasteController extends Controller
{
    public function __invoke(UnbindGenreTasteRequest $request)
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to unbind a genre from the User's taste.",
                    'error' => "No logged in users are found in the current session",
                    'data' => []
            ], 401);
        }

        try {
            User::unbindGenreFromUser($request->genre_id);
            $genres = Genre::getUsersTaste();

            return response()->json($genres);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to unbind a genre from the User's taste.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 401);
        }
    }
}
