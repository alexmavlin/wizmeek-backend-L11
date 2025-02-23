<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            // Validate the request input
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
                'remember' => 'nullable|boolean'
            ]);

            $user = User::query();
            $user->select('id', 'email', 'password');
            $user->where('email', $validated['email']);
            $user = $user->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => [
                        'email' => ['No user with this email found. SignUp please.']
                    ]
                ], 422);
            }

            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => [
                        'password' => ['Entered password does not match.']
                    ]
                ]);
            }


            $rememberMe = false;

            if (isset($validated['remember']) && $validated['remember'] === '1') {
                $rememberMe = true;
            }

            Auth::login($user, $rememberMe);

            // Simulate successful login for now
            return response()->json([
                'message' => 'Login successful!',
                'data' => $user,
            ], 200);

        } catch (ValidationException $e) {
            // Catch validation errors and return a JSON response
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle other exceptions and return a JSON response
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}