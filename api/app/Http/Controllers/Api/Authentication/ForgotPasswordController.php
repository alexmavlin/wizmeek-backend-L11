<?php

namespace App\Http\Controllers\Api\Authentication;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Jobs\SendResetPasswordEmail;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email|exists:users,email']);
    
            $token = Str::random(64);
    
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['token' => Hash::make($token), 'created_at' => Carbon::now()]
            );

            if (app()->environment('local')) {
                $resetLink = 'http://localhost:5000/reset-password?token=' . $token . '&email=' . urlencode($request->email);
            } else {
                $resetLink = 'https://wizmeek.com/reset-password?token=' . $token . '&email=' . urlencode($request->email);
            }
    
            SendResetPasswordEmail::dispatch($request->email, $resetLink);

            return response()->json([
                'success' => true,
                'message' => "Reset link sent to your email.",
                'error' => '',
                'data' => []
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $error->getMessage(),
                'data' => []
            ], 500);
        }

    }
}

