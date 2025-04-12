<?

namespace App\DataTransferObjects\MailDTO;

class FeedbackMessageDTO
{
    public function __construct(
        public string $subject,
        public string $message,
        public array $files = [],
        public string $userName
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            subject: $data['subject'] ?? '',
            message: $data['message'] ?? '',
            files: $data['files'] ?? [],
            userName: $data['userName'] ?? ''
        );
    }
}
