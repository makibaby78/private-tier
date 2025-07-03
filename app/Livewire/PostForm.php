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
        if (!Auth::check()) {
            abort(403, 'You must be logged in to create a post.');
        }

        $this->validate([
            'body' => 'required_without:media|string|nullable',
            'media' => 'required_without:body|array|max:10',
            'media.*' => 'file|mimetypes:image/*,video/*|max:102400',
        ]);

        $hasMedia = is_array($this->media) && count($this->media) > 0;

        $type = $hasMedia ? Post::TYPE_MEDIA : Post::TYPE_STATUS;

        $post = Post::create([
            'user_id' => Auth::id(),
            'body'    => $this->body,
            'type'    => $type,
        ]);

        foreach ($this->media ?? [] as $file) {
            $mimeType = $file->getMimeType();

            $path = Storage::disk('cloudinary')->putFile('posts', $file);
            $url = Storage::disk('cloudinary')->url($path);
            $publicId = pathinfo($path, PATHINFO_FILENAME);

            $mediaType = Str::startsWith($mimeType, 'video/') ? 'video' : 'image';

            $post->media()->create([
                'url'       => $url,
                'type'      => $mediaType,
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
