<?php

namespace App\Http\Controllers\Admin\Subscribers;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscribersIndexController extends Controller
{
    public function __invoke(Request $request) {
        $filterExpression = $request->input('filter_expression', '');

        try {
            $subscribers = Subscriber::getList($filterExpression);
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to load a subscribers list. Error: ' . $error->getMessage());
        }
        
        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [

            ],
            "subscribers" => $subscribers
        ];
        return view('admin.subscribers.subscribersindex', compact('data'));
    }
}
