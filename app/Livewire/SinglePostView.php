<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\User;

class SinglePostView extends Component
{
    public User $user;
    public Post $post;

    public function mount($username, Post $post)
    {
        $this->user = User::where('username', $username)->firstOrFail();

        // Ensure post belongs to the user in the URL
        if ($post->user_id !== $this->user->id) {
            abort(404);
        }

        $this->post = $post->load('user'); // preload user relationship
    }

    public function render()
    {
        return view('livewire.single-post-view')
            ->layout('layouts.app')
            ->title($this->post->user->name . ' - Post');
    }
}
