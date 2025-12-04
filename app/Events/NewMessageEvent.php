<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;


class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('private-chat.' . $this->message->receiver_id),
        ];
    }

    public function broadcastWith()
    {

        $photoPath = $this->message->sender->photo;
        $defaultPath = 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg';
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'content' => $this->message->content,
            'read_at' => $this->message->read_at,
            'created_at' => $this->message->created_at,
            'updated_at' => $this->message->updated_at,
            'sender' => [
                'id' => $this->message->sender->id,
                'first_name' => $this->message->sender->first_name,
                'last_name' => $this->message->sender->last_name,
                'email' => $this->message->sender->email,
                'photo' => $photoPath
                ? (str_starts_with($photoPath, 'http')
                    ? $photoPath
                    : asset('storage/profile_photos/' . basename($photoPath)))
                : $defaultPath ,
            ]
        ];
    }

    public function broadcastAs()
    {
        return 'message.new'; // Make sure event name matches
    }
}
