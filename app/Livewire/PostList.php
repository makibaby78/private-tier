<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class PostList extends Component
{
    public User $user;
    public $posts = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $this->posts = $this->user->posts()->latest()->get();
    }

    public function render()
    {
        return view('livewire.post-list');
    }
}

