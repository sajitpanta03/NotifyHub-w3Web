<?php

namespace App\Mail;

use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminToUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 600];
    public $logId;

    public function __construct(
        public $user,
        public $subject,
        public $message,
        public $adminName,
        $logId = null
    ) {
        $this->logId = $logId;

        if (empty($user->email)) {
            throw new \InvalidArgumentException('Recipient email is required');
        }
    }

    public function setLogId($logId)
    {
        $this->logId = $logId;
        return $this;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
            to: [$this->user->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-to-user',
            with: [
                'user' => $this->user,
                'subject' => $this->subject,
                'messageContent' => $this->message,
                'adminName' => $this->adminName,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function failed(\Throwable $exception): void
    {
        if ($this->logId) {
            EmailLog::where('id', $this->logId)->update([
                'status' => 'failed',
                'error' => $exception->getMessage()
            ]);
        }
    }
}
