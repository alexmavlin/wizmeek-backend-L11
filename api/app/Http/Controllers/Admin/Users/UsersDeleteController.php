<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersDeleteController extends Controller
{
    public function __invoke($id)
    {
        try {
            User::deleteWithRelations($id);

            return redirect()->back()->with('success', 'A user has been successfully deleted');
        } catch (Exception $error) {
            $message = "An error has occured during an attempt to delete a user. Error: " . $error->getMessage();
            Log::error($message);
            return redirect()->back()->with('error', $message);
        }
    }
}
