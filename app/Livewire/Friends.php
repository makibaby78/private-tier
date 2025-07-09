<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Friends extends Component
{
    public User $user;
    public $search = '';
    public $perPage = 20;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function loadMore()
    {
        $this->perPage += 20;
    }

    public function render()
    {

        $friends = $this->user->friends();

        if (!empty($this->search)) {
            $friends = $friends->filter(fn ($friend) =>
                stripos($friend->name, $this->search) !== false
            );
        }

        $friends = $friends->take($this->perPage);

        $friends = $friends->take($this->perPage)->map(function ($friend) {
            return (object) [
                'id' => $friend->id,
                'name' => $friend->name,
                'username' => $friend->username,
                'mutual_friends' => $this->calculateMutualFriends($friend),
                'profile_public_id' => $friend->profile_public_id ?? null,
            ];
        });

        return view('livewire.friends', [
            'friends' => $friends,
        ]);
    }

    protected function calculateMutualFriends(User $friend)
    {
        return $this->user->friends()->intersect($friend->friends())->count();
    }
}
