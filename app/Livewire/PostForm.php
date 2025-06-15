<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class PostForm extends Component
{
    use WithFileUploads;

    public $title = '';
    public $body = '';
    public $image;

    public function save()
    {
        $this->validate([
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);

        $imagePath = $this->image?->store('posts', 'public');

        Post::create([
            'user_id' => Auth::id(),
            'body' => $this->body,
            'image' => $imagePath,
        ]);

        $this->reset(); // reset fields including image
        session()->flash('message', 'Post created successfully.');
        $this->dispatch('post-created'); // emit event
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}
