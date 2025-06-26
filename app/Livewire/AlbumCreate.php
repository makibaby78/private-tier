<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class AlbumCreate extends Component
{
    public string $body = '';

    public function create()
    {
        $this->validate([
            'body' => 'required|string|max:255',
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'body' => $this->body,
            'type' => 'album',
        ]);

        $this->reset('body');

        session()->flash('message', 'Album created successfully.');
        $this->dispatch('album-created');
    }

    public function render()
    {
        return view('livewire.album-create');
    }
}
