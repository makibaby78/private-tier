<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostForm extends Component
{
    use WithFileUploads;

    public $title = '';
    public $body = '';
    public $media;

    public function save()
    {
        $this->validate([
            'body' => 'required_without:media|string|nullable',
            'media' => 'required_without:body|nullable|file|mimetypes:image/*,video/*|max:102400',
        ]);        

        $mediaUrl = null;
        $mediaType = null;
        $publicId = null;

        if ($this->media) {

            $mimeType = $this->media->getMimeType();

            // Upload to Cloudinary
            $path = Storage::disk('cloudinary')->putFile('posts', $this->media);
            $mediaUrl = Storage::disk('cloudinary')->url($path);

            $publicId = $path; // Save this in DB

            if (Str::startsWith($mimeType, 'image/')) {
                $mediaType = 'image';
            } elseif (Str::startsWith($mimeType, 'video/')) {
                $mediaType = 'video';
            }
        }

        Post::create([
            'user_id' => Auth::id(),
            'body' => $this->body,
            'public_id' => $publicId,
            'image' => $mediaType === 'image' ? $mediaUrl : null,
            'video' => $mediaType === 'video' ? $mediaUrl : null,
        ]);

        $this->reset();

        $this->dispatch('refresh-posts');

        session()->flash('message', 'Post created successfully.');
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}
