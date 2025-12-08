<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileEditController extends Controller
{
    public function __invoke()
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to load user's infomation.",
                    'error' => "No logged in users are found in the current session.",
                    'data' => []
            ], 401);
        }

        try {
            $result = User::getProfileDetailsForApiEdit();
            return response()->json([
                'success' => true,
                'message' => "User's data was loaded successfuly.",
                'error' => '',
                'data' => $result
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to load User's data.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
