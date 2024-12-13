<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserNavDetailsController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $userDetails = [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'doesNewNoticeExist' => true
        ];

        return response()->json($userDetails);
    }
}
