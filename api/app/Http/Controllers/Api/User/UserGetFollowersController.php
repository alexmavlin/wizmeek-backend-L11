<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserGetFollowersController extends Controller
{
    public function __invoke($uid)
    {
        try {
            $result = User::getFollowers($uid);
            return response()->json([
                'success' => true,
                'message' => 'Followers retrieved successfully.',
                'error' => '',
                'data' => $result
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to get followers.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
