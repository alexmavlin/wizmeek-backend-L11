<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserDeleteAccountRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserDeleteAccountController extends Controller
{
    public function __invoke(UserDeleteAccountRequest $request)
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to delete an account.",
                    'error' => "No logged in users are found in the current session",
                    'data' => []
            ], 401);
        }

        try {
            $result = User::handleApiDeleteAccount($request->user_id);
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
                'message' => "Unable to delete an account.",
                'error' => $exception->getMessage(),
                'data' => []
            ], $status);
        }
    }
}
