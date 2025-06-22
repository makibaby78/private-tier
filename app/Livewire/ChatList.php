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

            return [
                'id' => $friend->id,
                'name' => $friend->name,
                'profile_photo_path' => $friend->profile_photo_path,
                'last_message' => $lastMessage?->message ?? 'No messages yet',
                'last_time' => $lastMessage?->created_at?->diffForHumans(),
            ];
        })->sortByDesc('last_time')->values();
    }

    public function render()
    {
        return view('livewire.chat-list');
    }
}

