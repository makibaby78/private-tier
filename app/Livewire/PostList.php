<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Post;
use App\Models\PostMedia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class PostList extends Component
{
    use WithFileUploads;

    public User $user;
    public $posts = [];
    public $page = 1;
    public $perPage = 10;
    public $hasMore = true;

    public $editingPost;
    public $editingBody;
    public $newMedia = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadPosts();
    }

    #[On('refresh-posts')]
    public function refreshPosts()
    {
        $this->posts = [];
        $this->page = 1;
        $this->loadPosts();
    }

    public function openEditModal($postId)
    {
        $this->editingPost = Post::with('media')->findOrFail($postId);
        $this->editingBody = $this->editingPost->body;
        $this->newMedia = [];

        $this->dispatch('open-modal', name: 'edit-post-modal');
    }

    public function savePost()
    {
        $this->editingPost->body = $this->editingBody;
        $this->editingPost->save();

        foreach ($this->newMedia as $file) {
            $mimeType = $file->getMimeType();
            $path = Storage::disk('cloudinary')->putFile('posts', $file);
            $url = Storage::disk('cloudinary')->url($path);
            $publicId = pathinfo($path, PATHINFO_FILENAME);
            $type = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

            $this->editingPost->media()->create([
                'url' => $url,
                'public_id' => $publicId,
                'type' => $type,
            ]);
        }

        $this->dispatch('close-modal', name: 'edit-post-modal');
        $this->refreshPosts();

        session()->flash('message', 'Post updated.');
    }

    public function trashPost($postId)
    {
        $post = Post::with('media')->findOrFail($postId);

        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete all media from Cloudinary
        foreach ($post->media as $media) {
            if ($media->public_id) {
                Storage::disk('cloudinary')->delete("posts/{$media->public_id}");
            }
            $media->delete();
        }

        $post->delete();

        $this->refreshPosts();

        session()->flash('message', 'Post moved to trash.');
    }

    public function loadPosts()
    {
        $sliced = $this->user->posts()
            ->with('media')
            ->latest()
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        $this->posts = [...$this->posts, ...$sliced];
        $this->hasMore = $sliced->count() === $this->perPage;
    }

    public function loadMore()
    {
        $this->page++;
        $this->loadPosts();
    }

    public function render()
    {
        $currentPath = request()->path();
        
        return view('livewire.post-list', compact('currentPath'));
    }
}
