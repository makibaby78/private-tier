<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FriendRequestList extends Component
{
    public string $view = 'components.see-all';

    // use this for dynamic <livewire:friend-request-list :view="'components.another-view'" />

    public function accept($userId)
    {
        Auth::user()->friendOf()->updateExistingPivot($userId, ['status' => 'accepted']);
    }

    public function decline($userId)
    {
        Auth::user()->friendOf()->detach($userId);
    }

    public function render()
    {
        $requests = Auth::user()
            ->friendOf()
            ->wherePivot('status', 'pending')
            ->get();

        return view($this->view, compact('requests'));
    }
}
