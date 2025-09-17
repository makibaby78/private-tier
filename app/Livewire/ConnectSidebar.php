<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;


class ConnectSidebar extends Component
{
    public $search = '';

    public function getChatsProperty()
    {
        $userId = Auth::id();

        // Get last messages per conversation partner
        $messages = Message::query()
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('sender', fn ($s) => $s->where('firstname', 'like', "%{$this->search}%"))
                    ->orWhereHas('sender', fn ($r) => $r->where('lastname', 'like', "%{$this->search}%"))
                    ->orWhereHas('sender', fn ($r) => $r->where('firstname', 'like', "%{$this->search}%"))
                    ->orWhereHas('receiver', fn ($r) => $r->where('lastname', 'like', "%{$this->search}%"));
            })
            ->with(['sender', 'receiver'])
            ->latest('created_at')
            ->get()
            ->unique(function ($message) use ($userId) {
                // Ensure unique conversation partner
                return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
            });

        return $messages;
    }

    public function openConnect($userId)
    {
        $this->dispatch('connect-selected', userId: $userId)->to(\App\Livewire\ConnectWindow::class);
    }

    public function render()
    {
        return view('livewire.connect-sidebar', [
            'chats' => $this->chats,
        ]);
    }
}
