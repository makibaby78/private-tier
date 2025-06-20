<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;

class ChatManager extends Component
{
    public array $openChats = [];
    public array $messageInputs = [];
    public array $messages = [];

    #[On('open-chat')]
    public function openChat(int $userId)
    {
        if (isset($this->openChats[$userId])) {
            // Toggle minimized/open
            $this->openChats[$userId]['status'] = $this->openChats[$userId]['status'] === 'open' ? 'minimized' : 'open';
        } else {
            // First time open
            $this->openChats[$userId] = ['status' => 'open'];
            $this->messageInputs[$userId] = '';

            $this->messages[$userId] = Message::where(function ($q) use ($userId) {
                $q->where('sender_id', auth()->id())->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', auth()->id());
            })->with('sender')->orderBy('created_at')->get()->toArray();
        }

        // Dispatch to scroll when chat is reopened
        $this->dispatch('scroll-chat', userId: $userId);
    }

    public function sendMessage($userId)
    {
        $text = trim($this->messageInputs[$userId] ?? '');
        if ($text === '') return;

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $userId,
            'message' => $text,
        ]);

        $this->messages[$userId][] = $message->load('sender')->toArray();
        $this->messageInputs[$userId] = '';

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        // Dispatch to scroll down on send
        $this->dispatch('scroll-chat', userId: $userId);
    }

    public function closeChat($userId)
    {
        unset($this->openChats[$userId], $this->messageInputs[$userId], $this->messages[$userId]);
    }

    public function render()
    {
        return view('livewire.chat-manager');
    }
}
