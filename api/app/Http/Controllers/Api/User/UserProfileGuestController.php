<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileGuestController extends Controller
{
    public function __invoke(int $uid)
    {
        $userDetails = User::getProfileDetailsAsGuest($uid);

        return response()->json($userDetails);
    }
}
