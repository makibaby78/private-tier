<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ChatManager extends Component
{
    public array $openChats = [];

    #[On('open-chat')]
    public function openChat(int $userId)
    {
        

        // If already open and status is 'open', minimize it
        if (isset($this->openChats[$userId]) && $this->openChats[$userId]['status'] === 'open') {
            $this->openChats[$userId]['status'] = 'minimized';
        }
        // If already minimized, open it
        elseif (isset($this->openChats[$userId]) && $this->openChats[$userId]['status'] === 'minimized') {
            $this->openChats[$userId]['status'] = 'open';
        }
        // If not in list, open new chat
        else {
            $this->openChats[$userId] = ['status' => 'open'];
        }

        dd($this->openChats);
    }

    public function render()
    {
        return view('livewire.chat-manager');
    }
}
