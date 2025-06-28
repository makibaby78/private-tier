<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\PostMedia;

class MediaForm extends Component
{
    use WithFileUploads;

    public $postId; // 🆕 Accept the post ID
    public array $media = [];

    public function mount($postId = null)
    {
        $this->postId = $postId;
    }

    public function save()
    {
        $this->validate([
            'media.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov|max:10240',
        ]);

        foreach ($this->media as $file) {
            $path = Storage::disk('cloudinary')->putFile('posts', $file);
            $url = Storage::disk('cloudinary')->url($path);
            $publicId = pathinfo($path, PATHINFO_FILENAME);

            PostMedia::create([
                'post_id' => $this->postId,
                'url' => $url,
                'type' => 'image',
                'public_id' => $publicId,
            ]);
        }

        $this->reset('media');
        session()->flash('message', 'Media uploaded successfully!');
        $this->dispatch('media-uploaded');
    }

    public function render()
    {
        return view('livewire.media-form');
    }
}
