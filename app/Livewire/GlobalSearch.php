<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Post;

class GlobalSearch extends Component
{
    public string $q = '';

    public function render()
    {
        return view('livewire.global-search', [
            'users' => User::query()
                ->where('firstname', 'like', '%' . $this->q . '%')
                ->orWhere('lastname', 'like', '%' . $this->q . '%')
                ->orWhere('username', 'like', '%' . $this->q . '%')
                ->take(5)
                ->get(),

            'posts' => Post::query()
                ->where('body', 'like', '%' . $this->q . '%')
                ->take(5)
                ->get(),
        ]);
    }
}


