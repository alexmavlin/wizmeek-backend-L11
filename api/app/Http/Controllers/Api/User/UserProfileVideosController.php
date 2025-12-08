<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserProfileVideosRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfileVideosController extends Controller
{
    public function __invoke(UserProfileVideosRequest $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => "Unable to add video to profile.",
                'error' => "No logged in users are found in the current session.",
                'data' => []
            ], 401);
        }

        try {
            $user = auth()->user();

            if ($user) {
                if ($user->videosInProfile()->where('video_id', $request->video_id)->exists()) {
                    $user->videosInProfile()->detach($request->video_id);
                    $result = "detached";
                    $dataResponse = false;
                } else {
                    $user->videosInProfile()->attach($request->video_id);
                    $result = "attached";
                    $dataResponse = true;
                }
            }
            return response()->json([
                'success' => true,
                'message' => $result,
                'error' => '',
                'data' => [
                    "isInProfile" => $dataResponse
                ]
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to add video to profile.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
