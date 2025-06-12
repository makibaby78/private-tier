<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->receiver_id);
    }
    
    public function broadcastWith()
    {
        return [
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
        ];
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }
    
}
