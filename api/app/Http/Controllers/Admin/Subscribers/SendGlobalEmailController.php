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

        try {
            SendEmailsToSubscribers::dispatch($emailData);
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to send a global email. Error: ' . $error->getMessage());
        }

        return redirect()->route('admin_subscribers_index');
    }
}
