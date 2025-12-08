<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordConfrimationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $loginLink;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct(string $email, string $loginLink, string $name)
    {
        $this->email = $email;
        $this->loginLink = $loginLink;
        $this->name = $name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Wizmeek - Password Reset Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password-confirmation',
            with: [
                'email' => $this->email,
                'loginLink' => $this->loginLink,
                'name' => $this->name
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
