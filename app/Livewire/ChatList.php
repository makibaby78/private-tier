<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class ChatList extends Component
{
    public $friendsWithLastMessage = [];

    public function mount()
    {
        $user = Auth::user();
        $friends = $user->friends();

        // Loop through each friend and get last message between the two
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
                'photo' => $friend->profile_photo_url,
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

