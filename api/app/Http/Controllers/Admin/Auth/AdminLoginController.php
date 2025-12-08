<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AuthenticateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function login() {
        if (Auth::check()) {
            return redirect()->back();
        }
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_edit.scss'
            ],
            "js" => [

            ]
        ];
        return view('admin.auth.login', compact('data'));
    }

    public function authenticate(AuthenticateRequest $request) {
        $email = $request->email;
        $password = $request->password;

        $user = User::select('id', 'email', 'password', 'role')->where('email', $email)->first();

        if ($user) {
            if ($user->role != 'admin') {
                return redirect()->back()->withInput()->with('error', 'The email address you provided does not have the necessary permissions to access the admin panel.');
            }
            if (!Hash::check($password, $user->password)) {
                return redirect()->back()->withInput()->with('error', 'The password you entered is incorrect. Please try again or contact WizMeek technical support for assistance.');
            }
            Auth::login($user);
            return redirect()->route('admin_dashboard');
        } else {
            return redirect()->back()->withInput()->with('error', 'The email address you provided was not found in the system');
        }
    }

    public function logout() {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('admin_ligin');
        } else {
            return redirect()->back();
        }
    }
}
