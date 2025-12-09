<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    use SerializesModels;

    public $conversationId;
    public $user;

    public function __construct($conversationId, $user)
    {
        $this->conversationId = $conversationId;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user-activity.' . $this->conversationId);
    }

    public function broadcastAs()
    {
        return 'user.typing';
    }

    public function broadcastWith()
    {

        \Log::info('Broadcasting typing event:', [
            'user' => $this->user,
            'conversation_id' => $this->conversationId,
        ]);
        return [
            'user' => [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
            ],
            'conversation_id' => $this->conversationId,
        ];
    }
}
