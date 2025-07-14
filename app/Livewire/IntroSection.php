<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class IntroSection extends Component
{
    public User $user;
    public bool $isOwner;

    public function mount(User $user)
    {
        $this->user = $user->load([
            'currentJob' => fn ($q) => $q->where('visibility', 'public'),
            'previousJobs' => fn ($q) => $q->where('visibility', 'public'),
            'educations' => fn ($q) => $q->where('visibility', 'public'),
            'currentCity' => fn ($q) => $q->where('visibility', 'public'),
            'hometown' => fn ($q) => $q->where('visibility', 'public'),
            'relationship' => fn ($q) => $q->with('partner'),
            'contacts' => fn ($q) => $q->where('visibility', 'public'),
        ]);        
    }

    public function render()
    {
        $viewer = auth()->user();
        $relationship = $this->user->relationship;
    
        $isFriend = $viewer && $viewer->isFriendsWith($this->user);
        $isOwner = $viewer && $viewer->id === $this->user->id;
    
        $canViewRelationship = $relationship &&
            (
                $relationship->confirmed || $isOwner
            ) &&
            (
                $relationship->visibility === 'public'
                || ($relationship->visibility === 'friends' && ($isFriend || $isOwner))
                || ($relationship->visibility === 'only_me' && $isOwner)
            );
    
        return view('livewire.intro-section', [
            'relationship' => $relationship,
            'canViewRelationship' => $canViewRelationship,
            'isOwner' => $isOwner,
            'isFriend' => $isFriend,
        ]);
    }
    
}

