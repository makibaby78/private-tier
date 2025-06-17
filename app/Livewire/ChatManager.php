<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ChatManager extends Component
{
    #[On('send-message')]
    public function handleSendEvent($id)
    {

        dd($id);
        logger("Event received with ID: $id");
    }

    public function render()
    {
        return view('livewire.chat-manager');
    }
}
