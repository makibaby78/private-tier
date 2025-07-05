<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use Livewire\Attributes\On;

class ChatList extends Component
{
    public $friendsWithLastMessage = [];

    public function mount()
    {
        $this->loadFriendsWithLastMessage();
    }

    #[On('refresh-chat-list')]
    public function refreshChatList()
    {
        $this->loadFriendsWithLastMessage();
    }

    private function loadFriendsWithLastMessage()
    {
        $user = Auth::user();
        $friends = $user->friends();

        $this->friendsWithLastMessage = $friends->map(function ($friend) use ($user) {
            $lastMessage = Message::where(function ($query) use ($user, $friend) {
                    $query->where('sender_id', $user->id)
                          ->where('receiver_id', $friend->id);
                })
                ->orWhere(function ($query) use ($user, $friend) {
                    $query->where('sender_id', $friend->id)
                          ->where('receiver_id', $user->id);
                })
                ->orderByDesc('created_at')
                ->first();

                
            $displayMessage = null;
            if ($lastMessage) {
                $displayMessage = $lastMessage->type === 'text'
                    ? $lastMessage->body
                    : strtoupper($lastMessage->type); // e.g. IMAGE, VIDEO
            }
                

            return [
                'id' => $friend->id,
                'name' => $friend->name,
                'profile_public_id' => $friend->profile_public_id,
                'last_message' => $displayMessage ?? 'No messages yet',
                'last_time' => $lastMessage?->created_at?->diffForHumans(),
            ];
        })->sortByDesc('last_time')->values();
    }

    public function render()
    {
        return view('livewire.chat-list');
    }
}

