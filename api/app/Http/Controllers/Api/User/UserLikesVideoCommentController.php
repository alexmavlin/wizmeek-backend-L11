<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserLikesVideoCommentRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserLikesVideoCommentController extends Controller
{
    public function __invoke(UserLikesVideoCommentRequest $request)
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to like a video comment.",
                    'error' => "No logged in user is found in the current session",
                    'data' => []
            ], 401);
        }

        try {
            $result = User::handleLikedVideoComment($request->comment_id);
            return response()->json([
                'success' => true,
                'message' => $result,
                'error' => '',
                'data' => []
            ], 204);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to set like to a comment.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
