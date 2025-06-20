<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;


class PostList extends Component
{
    public User $user;
    public $posts = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadPosts();
    }

    #[On('refresh-posts')]
    public function refreshPosts()
    {
        $this->loadPosts();
    }

    public function editPost($postId)
    {
        $post = Post::findOrFail($postId);

        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->dispatch('openEditModal', post: $post);
    }

    public function trashPost($postId)
    {
        $post = Post::findOrFail($postId);

        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $post->delete();

        $this->refreshPosts();

        session()->flash('message', 'Post moved to trash.');
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

