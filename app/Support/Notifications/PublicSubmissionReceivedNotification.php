<?php

namespace App\Support\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PublicSubmissionReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  list<string>  $details
     */
    public function __construct(
        public string $subjectLine,
        public string $headline,
        public array $details,
        public string $reviewUrl,
    ) {
        $this->afterCommit();
        $this->onConnection('background');
        $this->onQueue('notifications');
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage())
            ->subject($this->subjectLine)
            ->greeting('Pemberitahuan admin')
            ->line($this->headline);

        foreach ($this->details as $detail) {
            $message->line($detail);
        }

        return $message
            ->action('Tinjau di panel admin', $this->reviewUrl)
            ->line('Notifikasi ini dikirim otomatis dari formulir publik website.');
    }
}
