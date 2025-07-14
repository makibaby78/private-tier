<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class IntroSection extends Component
{
    public User $user;
    public bool $isOwner;

    public function mount(User $user)
    {
        $viewer = Auth::user();

        $isOwner = $viewer && $viewer->id === $user->id;
        $isFriend = $viewer && $viewer->isFriendsWith($user);

        $this->user = $user->load([
            'currentJob' => fn ($q) => $isOwner || $isFriend
                ? $q->whereIn('visibility', ['public', 'friends'])
                : $q->where('visibility', 'public'),

            'previousJobs' => fn ($q) => $isOwner || $isFriend
                ? $q->whereIn('visibility', ['public', 'friends'])
                : $q->where('visibility', 'public'),

            'educations' => fn ($q) => $isOwner || $isFriend
                ? $q->whereIn('visibility', ['public', 'friends'])
                : $q->where('visibility', 'public'),

            'currentCity' => fn ($q) => $isOwner || $isFriend
                ? $q->whereIn('visibility', ['public', 'friends'])
                : $q->where('visibility', 'public'),

            'hometown' => fn ($q) => $isOwner || $isFriend
                ? $q->whereIn('visibility', ['public', 'friends'])
                : $q->where('visibility', 'public'),

            'contacts' => fn ($q) => $isOwner || $isFriend
                ? $q->whereIn('visibility', ['public', 'friends'])
                : $q->where('visibility', 'public'),

            'relationship' => fn ($q) => $q->with('partner'),
        ]);
    }

    public function render()
    {
        $viewer = auth()->user();
        $relationship = $this->user->relationship;

        $canViewRelationship = $relationship
            ? $relationship->canViewBy($viewer)
            : false;

        return view('livewire.intro-section', [
            'relationship' => $relationship,
            'canViewRelationship' => $canViewRelationship,
            'isOwner' => $viewer && $viewer->id === $this->user->id,
            'isFriend' => $viewer && $viewer->isFriendsWith($this->user),
        ]);
    }
}
