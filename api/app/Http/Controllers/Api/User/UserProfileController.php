<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function __invoke()
    {
        $authUser = Auth::user();

        $query = User::query();
        $query->where('id', $authUser->id);
        $query->select(
            'id',
            'name',
            'nickname',
            'avatar',
            'google_avatar',
            'created_at',
            'description'
        );
        $user = $query->first();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar ? $user->avatar : ($user->google_avatar ? $user->google_avatar : asset('img/artists/avatars/noAvatar.webp')),
            'description' => $user->description,
            'joined' => date('M Y', strtotime($user->created_at)),
            'following' => 15,
            'followed_by' => 165
        ]);
    }
}
