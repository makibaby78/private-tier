<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class MyFriendsList extends Component
{
    public string $filter = 'all';
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function setFilter(string $filter)
    {
        $this->filter = $filter;
    }

    public function render()
    {
        $friends = $this->user->friends();

        if ($this->filter !== 'all') {
            $friends = $friends->filter(function ($friend) {
                return $friend->pivot && $friend->pivot->tag === $this->filter;
            });
        }

        return view('livewire.my-friends-list', [
            'friends' => $friends,
        ]);
    }

}
