<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public User $user;
    public bool $isOwner;
    public $photos;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->isOwner = Auth::check() && Auth::id() === $user->id;
        $this->photos = $user->media()
            ->where('post_media.type', 'image')
            ->latest()
            ->take(9)
            ->get();
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
