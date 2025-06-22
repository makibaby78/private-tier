<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class Feed extends Component
{
    public $posts = [];
    public $page = 1;
    public $perPage = 10;
    public $hasMore = true;

    public function mount()
    {
        $this->loadFeed();
    }

    public function loadFeed()
    {
        $query = Post::all()
            ->sortByDesc(fn ($post) => $post->relevanceScore())
            ->values();

        $sliced = $query->forPage($this->page, $this->perPage)->values();

        $this->posts = [...$this->posts, ...$sliced];

        $this->hasMore = $query->count() > $this->postsCount();
    }

    public function loadMore()
    {
        $this->page++;
        $this->loadFeed();
    }

    public function postsCount()
    {
        return count($this->posts);
    }

    public function render()
    {
        return view('livewire.feed');
    }
}
