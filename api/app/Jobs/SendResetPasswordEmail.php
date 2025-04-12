<?

namespace App\Jobs;

use App\Mail\ResetPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $resetLink;

    public function __construct($email, $resetLink)
    {
        $this->email = $email;
        $this->resetLink = $resetLink;
    }

    public function handle()
    {
        Mail::mailer('mailjet')->to($this->email)->send(new ResetPasswordMail($this->resetLink));
    }
}
