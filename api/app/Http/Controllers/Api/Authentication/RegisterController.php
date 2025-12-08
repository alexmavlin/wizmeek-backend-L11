<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Authentication\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $newUserData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'day' => $request->day,
            'month' => $request->month,
            'year' => $request->year,
        ];

        try {
            $user = User::create($newUserData);
            Auth::login($user);
            return response()->json($user, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An attempt to register a new user failed',
                'errors' => [
                    'register' => [
                        'An attempt to create new user failed with error: ' . $e->getMessage()
                    ]
                ]
            ]);
        }
    }
}
