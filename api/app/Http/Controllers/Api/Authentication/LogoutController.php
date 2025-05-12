<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        Auth::logout(); // logs out the current user

        $request->session()->invalidate(); // invalidates the session
        $request->session()->regenerateToken(); // regenerates CSRF token

        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }
}
