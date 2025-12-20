<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Conversation;
use App\Livewire\ConnectWindow;
use Livewire\Attributes\On;


class ConnectSidebar extends Component
{
    public $search = '';
    public $chats;

    public function mount()
    {
        $this->loadChats();
    }

    public function loadChats()
    {
        $userId = auth()->id();

        $this->chats = Conversation::where(function ($q) use ($userId) {
                $q->where('user_one_id', $userId)
                  ->orWhere('user_two_id', $userId);
            })
            ->with(['lastMessage.sender'])
            ->orderByDesc('last_message_at')
            ->get();
    }

    #[On('sidebar-message-updated')]
    #[On('sidebar-received-updated')]
    public function updateReceived(int $conversationId, array $message): void
    {
        $chat = $this->chats->firstWhere('id', $conversationId);

        if ($chat) {
            // update existing
            $chat->last_message_at = $message['created_at'];
            $chat->lastMessage->message = $message['text'];

            // move to top
            $this->chats = $this->chats
                ->reject(fn ($c) => $c->id === $conversationId)
                ->prepend($chat);
        } else {
            // fallback: reload if not found
            $this->loadChats();
        }
    }

    public function openConnect($userId)
    {
        $this->dispatch('connect-selected', userId: $userId)->to(ConnectWindow::class);
    }

    public function render()
    {
        return view('livewire.connect-sidebar', [
            'chats' => $this->chats,
        ]);
    }
}
