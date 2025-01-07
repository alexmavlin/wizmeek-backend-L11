<?php

namespace App\Http\Controllers\Api\Genres;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Genres\BindGenreTasteRequest;
use App\Models\Genre;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class BindGenreTasteController extends Controller
{
    public function __invoke(BindGenreTasteRequest $request)
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to bind a genre to the User's taste.",
                    'error' => "No logged in users are found in the current session",
                    'data' => []
            ], 401);
        }

        try {
            User::bindGenreToUser($request->genre_id);
            $genres = Genre::getUsersTaste();

            return response()->json($genres);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to bind a genre to the User's taste.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 401);
        }
    }
}
