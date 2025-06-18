<?php

namespace App\Livewire;

use Livewire\Component;

class ChatManager extends Component
{
    public $logMessage = 'Waiting...';

    public int $userId;

    public function mount($userId = null)
    {
        $this->userId = $userId;
    }

    public function openChat()
    {
        $this->logMessage = "Received openChat for user ID: $this->userId";
    }

    public function render()
    {
        return view('livewire.chat-manager');
    }
}


