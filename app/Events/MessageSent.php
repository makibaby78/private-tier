<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->message->receiver_id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'type' => $this->message->type,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'conversation_id' => $this->message->conversation_id,
            'sender_name' => $this->message->sender->name,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'media'       => $this->message->media->map(fn ($media) => [
                'id'   => $media->id,
                'type' => $media->type,
                'url'     => $media->url,
                'caption' => $media->caption,
            ])->toArray()
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}
