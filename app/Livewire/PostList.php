<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


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
    public $newImage;

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
        $this->editingPost = Post::findOrFail($postId);
        $this->editingBody = $this->editingPost->body;
        $this->newImage = null;

        $this->dispatch('open-modal', name: 'edit-post-modal');
    }

    public function savePost()
    {
        $this->editingPost->body = $this->editingBody;

        if ($this->editingPost->image && $this->newImage) {
            // 🗑 Delete the old Cloudinary image using the stored public ID
            if ($this->editingPost->image_public_id) {
                Storage::disk('cloudinary')->delete($this->editingPost->image);
            }

            // ☁️ Upload new image and get the path
            $uploadedPath = Storage::disk('cloudinary')->putFile('posts', $this->newImage);

            // 🔗 Save new image URL and public_id
            $this->editingPost->image = Storage::disk('cloudinary')->url($uploadedPath);
            $this->editingPost->image_public_id = $uploadedPath;
        }

        $this->editingPost->save();

        $this->dispatch('close-modal', name: 'edit-post-modal');

        $this->refreshPosts();

        session()->flash('message', 'Post Updated.');
    }

    public function trashPost($postId)
    {
        $post = Post::findOrFail($postId);

        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // ✅ Delete media from Cloudinary if public_id exists
        if ($post->public_id) {
            Storage::disk('cloudinary')->delete($post->public_id);
        }

        // ✅ Then delete the post from DB
        $post->delete();

        $this->refreshPosts();

        session()->flash('message', 'Post moved to trash.');
    }


    public function loadPosts()
    {
        $sliced = $this->user->posts()
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

    public function postsCount()
    {
        return count($this->posts);
    }

    public function render()
    {
        return view('livewire.post-list');
    }
}

