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
        // Load only the most recent N posts (for efficiency), with eager-loaded media
        $posts = Post::with('media')
            ->take(100) // tune this: how many posts max to load per request
            ->get();
    
        // Sort by relevanceScore in PHP
        $sorted = $posts->sortByDesc(fn($post) => $post->relevanceScore())->values();
    
        // Paginate results manually
        $sliced = $sorted->forPage($this->page, $this->perPage)->values();
    
        // Append or replace posts
        if ($this->page === 1) {
            $this->posts = $sliced;
        } else {
            $this->posts = [...$this->posts, ...$sliced];
        }
    
        $this->hasMore = $sorted->count() > $this->postsCount();
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
