<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserFollowsArtistRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserUnfollowArtistController extends Controller
{
    public function __invoke(UserFollowsArtistRequest $request)
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to unfollow an artist.",
                    'error' => "No logged in users are found in the current session",
                    'data' => []
            ], 401);
        }

        try {
            $result = User::handleApiUnfollowArtists($request->artist_id);
            return response()->json([
                'success' => true,
                'message' => $result,
                'error' => '',
                'data' => []
            ], 202);
        } catch (\Exception $exception) {
            if ($exception instanceof HttpException) {
                $status = $exception->getStatusCode();
            } else {
                $status = 500;
            }
        
            return response()->json([
                'success' => false,
                'message' => "Unable to unfollow an artist.",
                'error' => $exception->getMessage(),
                'data' => []
            ], $status);
        }
    }
}
