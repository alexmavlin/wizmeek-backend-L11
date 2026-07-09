<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DevLoginController extends Controller
{
    /**
     * Fixed development token used to bypass the password check.
     */
    private const DEV_TOKEN = '098765';

    public function __invoke(Request $request)
    {
        // Only available outside production to avoid a password bypass in prod.
        if (app()->environment('production') && !config('app.debug')) {
            abort(404);
        }

        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
                'remember' => 'nullable|boolean',
            ]);

            if ($validated['token'] !== self::DEV_TOKEN) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => [
                        'token' => ['Invalid dev token.'],
                    ],
                ], 422);
            }

            $user = User::query()
                ->select('id', 'email', 'password')
                ->where('email', $validated['email'])
                ->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => [
                        'email' => ['No user with this email found. SignUp please.'],
                    ],
                ], 422);
            }

            $rememberMe = isset($validated['remember']) && (string) $validated['remember'] === '1';

            Auth::login($user, $rememberMe);

            return response()->json([
                'message' => 'Dev login successful!',
                'data' => $user,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
