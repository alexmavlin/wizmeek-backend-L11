<?php

namespace App\Jobs;

use App\DataTransferObjects\MailDTO\FeedbackMessageDTO;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendFeedbackMessageToSupport implements ShouldQueue
{
    use Queueable;

    public FeedbackMessageDTO $data;

    /**
     * Create a new job instance.
     */
    public function __construct(FeedbackMessageDTO $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
