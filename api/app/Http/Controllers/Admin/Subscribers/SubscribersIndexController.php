<?php

namespace App\Http\Controllers\Admin\Subscribers;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscribersIndexController extends Controller
{
    public function __invoke(Request $request) {
        $filterExpression = $request->input('filter_expression', '');
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [

            ],
            "subscribers" => Subscriber::getList($filterExpression),
        ];
        return view('admin.subscribers.subscribersindex', compact('data'));
    }
}
