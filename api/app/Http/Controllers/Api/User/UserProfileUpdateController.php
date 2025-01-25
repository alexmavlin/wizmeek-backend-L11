<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserDescriptionUpdateRequest;
use App\Http\Requests\Api\User\UserNameEmailUpdateRequest;
use App\Http\Requests\Api\User\UserPasswordUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileUpdateController extends Controller
{
    public function updateDescription(UserDescriptionUpdateRequest $request)
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to update user's infomation.",
                    'error' => "No logged in users are found in the current session.",
                    'data' => []
            ], 401);
        }

        try {
            $user = User::find(Auth::user()->id);
            $user->description = $request->description;
            $result = $user->save();
            return response()->json([
                'success' => $result,
                'user' => $user,
                'description' => $request->description,
                'message' => "User's data was updated successfuly.",
                'error' => '',
                'data' => []
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to update user's data.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }

    public function updateNameAndEmail(UserNameEmailUpdateRequest $request)
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to update user's infomation.",
                    'error' => "No logged in users are found in the current session.",
                    'data' => []
            ], 401);
        }

        try {
            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $result = $user->save();
            return response()->json([
                'success' => $result,
                'user' => $user,
                'description' => $request->description,
                'message' => "User's data was updated successfuly.",
                'error' => '',
                'data' => []
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to update user's data.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }

    public function updatePassword(UserPasswordUpdateRequest $request) {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to update user's infomation.",
                    'error' => "No logged in users are found in the current session.",
                    'data' => []
            ], 401);
        }

        if (!Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
                'success' => false,
                'message' => "Unable to update user's infomation.",
                'error' => "Incorrect password.",
                'data' => []
            ], 422);
        }

        try {
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->new_password);
            $result = $user->save();
            return response()->json([
                'success' => $result,
                'user' => $user,
                'description' => $request->description,
                'message' => "User's data was updated successfuly.",
                'error' => '',
                'data' => []
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to update user's data.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
