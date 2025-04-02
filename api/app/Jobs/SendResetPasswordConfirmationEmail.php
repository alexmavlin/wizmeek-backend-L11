<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $name;
    public $loginLink;

    public function __construct($email, $loginLink, $name)
    {
        $this->email = $email;
        $this->loginLink = $loginLink;
        $this->name = $name;
    }

    public function handle()
    {
        Mail::send('emails.reset-password-confirmation', [
            'email' => $this->email,
            'loginLink' => $this->loginLink,
            'name' => $this->name
        ], function ($message) {
            $message->to($this->email)->subject('Wizmeek - Password Reset Confirmation');
        });
    }
}
