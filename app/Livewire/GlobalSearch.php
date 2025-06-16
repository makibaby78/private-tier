<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Post;

class GlobalSearch extends Component
{
    public string $query = '';

    public function render()
    {
        return view('livewire.global-search', [
            'users' => User::query()
                ->where('firstname', 'like', '%' . $this->query . '%')
                ->orWhere('lastname', 'like', '%' . $this->query . '%')
                ->orWhere('username', 'like', '%' . $this->query . '%')
                ->take(5)
                ->get(),

            'posts' => Post::query()
                ->where('body', 'like', '%' . $this->query . '%')
                ->take(5)
                ->get(),
        ]);
    }
}


