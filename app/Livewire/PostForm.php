<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostMedia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ProfilePicture;

class PostForm extends Component
{
    use WithFileUploads;

    public $body = '';
    public string $from;
    public array $media = [];

    public function mount(string $from)
    {
        $this->from = $from;
    }

    public function save()
    {
        if (!Auth::check()) {
            abort(403, 'You must be logged in to create a post.');
        }

        $rules = [];
        $cloudinary_directory = '';

        if ($this->from === 'profile') {

            $cloudinary_directory = 'profile-photos';

            $rules['body'] = 'nullable|string';
            $rules['media'] = 'required|array|size:1';
            $rules['media.*'] = 'file|mimetypes:image/*,video/*|max:102400';

        } else {

            $cloudinary_directory = 'posts';

            $rules['body'] = 'required_without:media|string|nullable';
            $rules['media'] = 'required_without:body|array|max:10';
            $rules['media.*'] = 'file|mimetypes:image/*,video/*|max:102400';

        }

        $this->validate($rules);

        $hasMedia = is_array($this->media) && count($this->media) > 0;

        $type = $hasMedia ? Post::TYPE_MEDIA : Post::TYPE_STATUS;

        $post = Post::create([
            'user_id' => Auth::id(),
            'body'    => $this->body,
            'type'    => $type,
        ]);

        if ($this->from === 'profile') {

            $user = Auth::user();

            ProfilePicture::whereIn('post_id', $user->posts()->pluck('id'))
            ->update(['is_current' => false]);

            ProfilePicture::create([
                'post_id' => $post->id,
                'is_current' => true,
            ]);
        }

        foreach ($this->media ?? [] as $file) {
            $mimeType = $file->getMimeType();

            $path = Storage::disk('cloudinary')->putFile($cloudinary_directory, $file);
            $url = Storage::disk('cloudinary')->url($path);
            $publicId = pathinfo($path, PATHINFO_FILENAME);

            $mediaType = Str::startsWith($mimeType, 'video/') ? 'video' : 'image';

            $post->media()->create([
                'url'       => $url,
                'type'      => $mediaType,
                'public_id' => $path,
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
