<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
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
    
        $contactIds = Message::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
        ->get()
        ->map(function ($message) use ($user) {
            return $message->sender_id === $user->id
                ? $message->receiver_id
                : $message->sender_id;
        })
        ->unique()
        ->values();

        $contacts = User::whereIn('id', $contactIds)->get();

        $this->friendsWithLastMessage = $contacts->map(function ($contact) use ($user) {
            $lastMessage = Message::where(function ($query) use ($user, $contact) {
                    $query->where('sender_id', $user->id)
                          ->where('receiver_id', $contact->id);
                })
                ->orWhere(function ($query) use ($user, $contact) {
                    $query->where('sender_id', $contact->id)
                          ->where('receiver_id', $user->id);
                })
                ->orderByDesc('created_at')
                ->first();

            $displayMessage = $lastMessage
                ? ($lastMessage->type === 'text' ? $lastMessage->body : strtoupper($lastMessage->type))
                : 'No messages yet';
    
            // Determine if the last message is unread (receiver is the current user and read_at is null)
            $isUnread = $lastMessage &&
                        $lastMessage->receiver_id === $user->id &&
                        is_null($lastMessage->read_at);
    
            return [
                'id' => $contact->id,
                'name' => $contact->name,
                'profile_public_id' => $contact->profile_public_id,
                'last_message' => $displayMessage,
                'last_time' => $lastMessage?->created_at?->diffForHumans() ?? null,
                'last_time_sort' => $lastMessage?->created_at ?? now()->subYears(10),
                'is_unread' => $isUnread,
            ];
        })->sortByDesc('last_time_sort')->values();
    }

    public function markAsRead($contactId)
    {
        Message::where('sender_id', $contactId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->loadFriendsWithLastMessage();
    }

    public function render()
    {
        return view('livewire.chat-list');
    }
}

