<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        $imageUrl = null;

        if ($this->image) {
            // Upload to Cloudinary via Laravel filesystem driver
            $publicId = Storage::disk('cloudinary')->putFile('posts', $this->image);

            // Generate the full URL to the uploaded asset
            $imageUrl = Storage::disk('cloudinary')->url($publicId);
        }

        Post::create([
            'user_id' => Auth::id(),
            'body' => $this->body,
            'image' => $imageUrl,
        ]);

        $this->reset();
        session()->flash('message', 'Post created successfully.');
        $this->dispatch('post-created');
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}
