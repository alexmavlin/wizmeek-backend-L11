<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class UsersViewController extends Controller
{
    public function __invoke()
    {
        try {
            $data = [
                "scss" => [
                    'resources/scss/admin/artists/artists_view.scss',
                    'resources/scss/admin/artists/artists_index.scss',
                    'resources/scss/admin/videos/video_view.scss',
                ],
                "js" => [],
                "users" => User::getForAdmin(),
            ];
            // dd($data);
            return view('admin.users.usersindex', compact('data'));
        } catch (Exception $error) {
            $message = "An error has occured during an attemp to access users list. Error: " . $error->getMessage();
            Log::error($message);
            return redirect()->back()->with('error', $message);
        }
    }
}
