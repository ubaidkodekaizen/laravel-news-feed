<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewMessageNotification;

class SendOfflineMessageNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sender;
    protected $receiver;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param User $sender
     * @param User $receiver
     * @param Message $message
     * @return void
     */
    public function __construct(User $sender, User $receiver, Message $message)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Check if message was already read (user might have come online)
        if ($this->message->read_at) {
            \Log::info('Message already read, skipping email notification', [
                'message_id' => $this->message->id
            ]);
            return;
        }
        
        // Send the email notification
        try {
            Mail::to($this->receiver->email)
                ->send(new NewMessageNotification($this->sender, $this->message));
                
            \Log::info('Offline message email notification sent', [
                'receiver_id' => $this->receiver->id,
                'message_id' => $this->message->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send offline message notification email', [
                'error' => $e->getMessage(),
                'receiver_id' => $this->receiver->id
            ]);
        }
    }
}