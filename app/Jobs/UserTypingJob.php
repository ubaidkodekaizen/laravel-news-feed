<?php

namespace App\Jobs;

use App\Events\UserTyping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserTypingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $conversationId;
    public $user;

    public function __construct($conversationId, $user)
    {
        $this->conversationId = $conversationId;
        $this->user = $user;
    }

    public function handle()
    {
        // Broadcast the UserTyping event
        broadcast(new UserTyping($this->conversationId, $this->user))->toOthers();
    }
}
