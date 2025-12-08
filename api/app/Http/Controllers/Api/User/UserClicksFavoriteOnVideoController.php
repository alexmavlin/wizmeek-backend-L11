<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserClicksFavoriteOnVideoRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserClicksFavoriteOnVideoController extends Controller
{
    public function __invoke(UserClicksFavoriteOnVideoRequest $request)
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
            $result = User::handleFavoritedVideo($request->video_id);
            return response()->json([
                'success' => true,
                'message' => $result,
                'error' => '',
                'data' => []
            ], 204);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to bind a genre to the User's taste.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
