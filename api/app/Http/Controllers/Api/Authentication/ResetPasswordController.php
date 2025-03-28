<?php

namespace App\Http\Controllers\Api\Authentication;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Jobs\SendResetPasswordConfirmationEmail;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Handle password reset request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:4|confirmed',
        ]);

        try {
            $passwordReset = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$passwordReset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired token.',
                ], 400);
            }
            if (!Hash::check($request->token, $passwordReset->token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired token.',
                ], 400);
            }

            if (Carbon::parse($passwordReset->created_at)->addMinutes(20)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The reset link has expired.',
                ], 400);
            }

            $user = User::where('email', $request->email)
                            ->select('id', 'email', 'name', 'password')
                            ->first();
            $user->password = Hash::make($request->password);

            $user->update(['password' => $user->password]);

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            if (app()->environment('local')) {
                $loginLink = 'http://localhost:5000/login';
            } else {
                $loginLink = 'https://wizmeek.com/login';
            }

            SendResetPasswordConfirmationEmail::dispatch($request->email, $loginLink, $user->name);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully. A confirmation email has been sent.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

