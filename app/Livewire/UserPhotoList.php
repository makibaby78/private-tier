<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserPhotoList extends Component
{
    use WithPagination;

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $photos = $this->user
            ->media()
            ->where('post_media.type', 'image') 
            ->latest()
            ->paginate(24);

        return view('livewire.user-photo-list', compact('photos'));
    }
}
