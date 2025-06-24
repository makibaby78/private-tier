<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostMedia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostForm extends Component
{
    use WithFileUploads;

    public $body = '';
    public array $media = [];

    public function save()
    {
        $this->validate([
            'body' => 'required_without:media|string|nullable',
            'media' => 'required_without:body|array|max:10',
            'media.*' => 'file|mimetypes:image/*,video/*|max:102400',
        ]);

        // Create the post
        $post = Post::create([
            'user_id' => Auth::id(),
            'body' => $this->body,
        ]);

        // Upload each media file
        foreach ($this->media as $file) {
            $mimeType = $file->getMimeType();
            $path = Storage::disk('cloudinary')->putFile('posts', $file);
            $url = Storage::disk('cloudinary')->url($path);
            $publicId = pathinfo($path, PATHINFO_FILENAME);

            $type = Str::startsWith($mimeType, 'video/') ? 'video' : 'image';

            $post->media()->create([
                'url' => $url,
                'type' => $type,
                'public_id' => $publicId,
            ]);
        }

        $this->reset();

        $this->dispatch('refresh-posts');

        $this->dispatch('post-created');

        session()->flash('message', 'Post created successfully.');
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}
