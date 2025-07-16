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
    public string $activeTab = 'All friends';

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function loadMore()
    {
        $this->perPage += 20;
    }

    public function setActiveTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $friends = $this->user->friends();

        if (!empty($this->search)) {
            $friends = $friends->filter(fn ($friend) =>
                stripos($friend->name, $this->search) !== false
            );
        }

        if ($this->activeTab === 'Birthdays') {
            $today = now()->format('m-d');
            $friends = $friends->filter(function ($friend) use ($today) {
                if (!$friend->birthdate) return false;
                return $friend->birthdate->format('m-d') === $today;
            });
        }

        $friends = $friends->take($this->perPage);

        $friends = $friends->map(function ($friend) {
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
