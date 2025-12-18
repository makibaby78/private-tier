<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ConnectWindow extends Component
{
    public ?int $partnerId = null;
    public Collection $messages;
    public int $perPage = 30;

    public array $messageInputs = [];
    
    public function mount()
    {
        $this->messages = collect();
    }    

    public function connectSend(int $userId): void
    {
        $text = trim($this->messageInputs[$userId] ?? '');
    
        if ($text === '' && empty($this->media)) {
            return;
        }
    
        $authUser = auth()->user();
    
        // ❌ Prevent chatting with yourself
        if ($authUser->id === $userId) {
            return;
        }
    
        // Normalize conversation user IDs
        $userOne = min($authUser->id, $userId);
        $userTwo = max($authUser->id, $userId);
    
        // 1️⃣ Get or create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'user_one_id' => $userOne,
                'user_two_id' => $userTwo,
            ],
            [
                'last_message_at' => now(),
            ]
        );
    
        // 2️⃣ Create message
        if ($text !== '') {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $authUser->id,
                'receiver_id'     => $userId,
                'message'         => $text,
                'type'            => 'text',
            ]);
    
            // 3️⃣ Update conversation metadata
            $conversation->update([
                'last_message_at' => $message->created_at,
                'last_message_id' => $message->id, // optional but recommended
            ]);

            // 4️⃣ Push message into Livewire state for instant UI
            $this->messages->push([
                'id'         => $message->id,
                'message'    => $message->message,
                'type'       => $message->type,
                'sender_id'  => $message->sender_id,
                'receiver_id'=> $message->receiver_id,
                'created_at' => $message->created_at,
                'sender' => [
                    'id'   => $authUser->id,
                    'name' => $authUser->name,
                ],
            ]);
        }

        broadcast(new \App\Events\MessageSent($message))->toOthers();
    
        // 4️⃣ Clear input
        unset($this->messageInputs[$userId]);
    }    

    #[On('connect-selected')]
    public function loadChat(int $userId): void
    {
        // set partner id
        $this->partnerId = (int) $userId;
    
        // defensive: ensure partnerId present and not the same as the authenticated user
        $me = auth()->id();
        if (! $this->partnerId || $this->partnerId === $me) {
            // clear messages if bad partner or self
            $this->messages = collect();
            return;
        }
    
        // ensure default page size
        $this->perPage ??= 30;
    
        $partner = $this->partnerId;
    
        // fetch the latest N messages between me <-> partner, then reverse so oldest is first
        $msgs = Message::where(function ($q) use ($me, $partner) {
                $q->where('sender_id', $me)->where('receiver_id', $partner);
            })->orWhere(function ($q) use ($me, $partner) {
                $q->where('sender_id', $partner)->where('receiver_id', $me);
            })
            ->with('sender')                // eager-load sender for display (avatar/name)
            ->latest('created_at')         // newest first
            ->take($this->perPage)
            ->get()
            ->reverse()                    // now oldest first
            ->values();
    
        // mark partner->me messages as read (only those not already read)
        Message::where('sender_id', $partner)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    
        // set messages for the view
        $this->messages = $msgs->map(fn ($msg) => [
            'id'         => $msg->id,
            'message'    => $msg->message,
            'type'       => $msg->type,
            'sender_id'  => $msg->sender_id,
            'receiver_id'=> $msg->receiver_id,
            'created_at' => $msg->created_at,
            'sender' => [
                'id'   => $msg->sender->id,
                'name' => $msg->sender->name,
            ],
        ]);
    
        // optionally notify sidebar (if you have a ChatSidebar Livewire component that should refresh)
        // remove this line if you don't use it:
        $this->dispatch('chat-loaded', userId: $partner)->to(\App\Livewire\ConnectSidebar::class);
    }

    #[On('message-received')]
    public function connectIncomingMessage(
        int $id,
        string $message,
        int $sender_id,
        string $sender_name,
        string $type
    ): void {

        if ($this->messages->contains('id', $id)) {
            return;
        }

        if ($sender_id !== $this->partnerId) {
            return;
        }

        $this->messages->push([
            'id'         => $id,
            'message'    => $message,
            'type'       => $type,
            'sender_id'  => $sender_id,
            'receiver_id'=> auth()->id(),
            'created_at' => now(),
            'sender' => [
                'id'   => $sender_id,
                'name' => $sender_name,
            ],
        ]);

        $this->messages = $this->messages->sortBy('created_at')->values();

        Message::where('sender_id', $sender_id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

    }

    public function render()
    {
        return view('livewire.connect-window');
    }
}
