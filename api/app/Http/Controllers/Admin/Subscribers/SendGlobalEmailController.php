<?php

namespace App\Http\Controllers\Admin\Subscribers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subscribers\SendGlobalEmailRequest;
use App\Jobs\SendEmailsToSubscribers;
use Illuminate\Http\Request;

class SendGlobalEmailController extends Controller
{
    public function __invoke(SendGlobalEmailRequest $request) 
    {
        $emailData = [
            'subject' => $request->title,
            'body' => $request->message,
        ];

        SendEmailsToSubscribers::dispatch($emailData);

        return redirect()->route('admin_subscribers_index');
    }
}
