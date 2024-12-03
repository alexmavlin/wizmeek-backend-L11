<?php

namespace App\Http\Controllers\Admin\Subscribers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditGlobalEmailController extends Controller
{
    public function __invoke()
    {
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_edit.scss'
            ],
            "js" => [

            ]
        ];
        return view('admin.subscribers.editglobalemail', compact('data'));
    }
}
