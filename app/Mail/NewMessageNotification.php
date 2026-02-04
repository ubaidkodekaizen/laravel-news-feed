<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Users\User;
use App\Models\Chat\Message;

class NewMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $message;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Users\User $sender
     * @param Message $message
     * @return void
     */
    public function __construct(User $sender, Message $message)
    {
        $this->sender = $sender;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("New Message from {$this->sender->first_name} on Muslim Linker")
                    ->markdown('emails.new-message-notification')
                    ->with([
                        'senderName' => "{$this->sender->first_name} {$this->sender->last_name}",
                        'messagePreview' => $this->getMessagePreview(),
                        'messageUrl' => url('/messages'),
                        'senderProfileUrl' => url("/user/profile/{$this->sender->slug}"),
                    ]);
    }
    
    /**
     * Get a preview of the message (truncated if too long)
     *
     * @return string
     */
    protected function getMessagePreview()
    {
        $content = $this->message->content;
        
        if (strlen($content) > 100) {
            return substr($content, 0, 97) . '...';
        }
        
        return $content;
    }
}