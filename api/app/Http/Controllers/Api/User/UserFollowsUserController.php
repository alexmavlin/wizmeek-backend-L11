<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserFollowsUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserFollowsUserController extends Controller
{
    public function __invoke(UserFollowsUserRequest $request)
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to follow a user.",
                    'error' => "No logged in users are found in the current session",
                    'data' => []
            ], 401);
        }

        try {
            $result = User::handleApiFollowUsers($request->id);
            return response()->json([
                'success' => true,
                'message' => $result,
                'error' => '',
                'data' => []
            ], 204);
        } catch (\Exception $exception) {
            if ($exception instanceof HttpException) {
                $status = $exception->getStatusCode();
            } else {
                $status = 500;
            }
        
            return response()->json([
                'success' => false,
                'message' => "Unable to follow a user.",
                'error' => $exception->getMessage(),
                'data' => []
            ], $status);
        }
    }
}
