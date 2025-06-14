<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendshipButton extends Component
{
    public User $targetUser;

    public function sendRequest()
    {
        if (!auth()->check()) return;
    
        auth()->user()->friendsOfMine()->attach($this->targetUser->id, ['status' => 'pending']);
    }

    public function cancelRequest()
    {
        if (!auth()->check()) return;
    
        auth()->user()->friendsOfMine()->detach($this->targetUser->id);
    }
    
    public function acceptRequest()
    {
        if (!auth()->check()) return;
    
        auth()->user()->friendOf()->updateExistingPivot($this->targetUser->id, ['status' => 'accepted']);
    }
    
    public function unfriend()
    {
        if (!auth()->check()) return;
    
        $user = auth()->user();
        $user->friendsOfMine()->detach($this->targetUser->id);
        $user->friendOf()->detach($this->targetUser->id);
    }
    

    public function render()
    {
        $status = null;
    
        if (auth()->check()) {
            $authUser = auth()->user();
    
            if ($authUser->isFriendsWith($this->targetUser)) {
                $status = 'friends';
            } elseif ($authUser->hasSentFriendRequestTo($this->targetUser)) {
                $status = 'sent';
            } elseif ($authUser->hasReceivedFriendRequestFrom($this->targetUser)) {
                $status = 'received';
            }
        }
    
        return view('livewire.friendship-button', compact('status'));
    }
    
}
