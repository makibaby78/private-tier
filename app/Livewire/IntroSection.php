<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class IntroSection extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user->load([
            'currentJob' => fn ($query) => $query->where('visibility', 'public'),
            'previousJobs' => fn ($query) => $query->where('visibility', 'public'),
            'educations' => fn ($query) => $query->where('visibility', 'public'),
            'currentCity' => fn ($query) => $query->where('visibility', 'public'),
            'hometown' => fn ($query) => $query->where('visibility', 'public'),
            'relationship' => fn ($query) => $query->where('visibility', 'public'),
            'contacts' => fn ($query) => $query->where('visibility', 'public'),
        ]);
    }

    public function render()
    {
        return view('livewire.intro-section');
    }
}

