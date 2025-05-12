<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserGetFollowedUsersController extends Controller
{
    public function __invoke($uid)
    {
        try {
            $result = User::getFollowed($uid);
            return response()->json([
                'success' => true,
                'message' => 'Followed users retrieved successfully.',
                'error' => '',
                'data' => $result
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to get followed users.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
