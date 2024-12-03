<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $data = [
            "scss" => [
                "resources/scss/admin/dashboard.scss"
            ],
            "js" => [

            ],
            "user_stats" => [
                "total_users" => User::getTotalUsers(),
                "today_registered_users" => User::getTodayRegisteredUsers()
            ],
        ];
        return view('admin.dashboard', compact('data'));
    }
}
