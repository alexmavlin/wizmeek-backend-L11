<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserAvatarUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserAvatarUpdateController extends Controller
{
    public function __invoke(UserAvatarUpdateRequest $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => "Unable to update user's avatar.",
                'error' => "No logged in users are found in the current session.",
                'data' => []
            ], 401);
        }

        $user = User::find(Auth::user()->id);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');

            if (!$avatar->isValid() || !in_array($avatar->extension(), ['jpg', 'jpeg', 'png'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid avatar file type or upload error.',
                    'data' => []
                ], 400);
            }

            if ($user->avatar) {
                $previousAvatarPath = public_path("img/avatars/{$user->avatar}");
                if (file_exists($previousAvatarPath)) {
                    unlink($previousAvatarPath);
                }
            }

            $avatarName = Str::uuid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('img/avatars'), $avatarName);

            $user->avatar = $avatarName;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => "User's avatar updated successfully.",
                'data' => ['avatar' => asset("img/avatars/{$avatarName}")]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "No avatar file provided.",
            'data' => []
        ], 400);
    }
}