<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class Feed extends Component
{
    public $posts = [];

    public function mount()
    {
        $this->loadFeed();
    }

    public function loadFeed()
    {
        $this->posts = Post::all()
            ->sortByDesc(fn ($post) => $post->relevanceScore())
            ->values();
    }

    public function render()
    {
        return view('livewire.feed');
    }
}
