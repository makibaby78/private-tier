<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UserRelationship;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RelationshipSection extends Component
{
    public $user;
    public $isOwner;
    public $isFriend;

    public $status = 'single';
    public $partner_id;
    public $since;
    public $visibility = 'public';

    public $editing = false;
    public $relationship;

    public function mount($user, $isOwner, $isFriend = false)
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
        $this->isFriend = $isFriend;

        $this->relationship = UserRelationship::where('user_id', $user->id)->first();

        if ($this->relationship) {
            $this->status = $this->relationship->status;
            $this->partner_id = $this->relationship->partner_id;
            $this->since = $this->relationship->since;
            $this->visibility = $this->relationship->visibility;
        }
    }

    public function save()
    {
        $data = $this->validate([
            'status' => 'required|string',
            'partner_id' => 'nullable|exists:users,id',
            'since' => 'nullable|date',
            'visibility' => 'required|in:public,friends,only_me',
        ]);

        UserRelationship::updateOrCreate(
            ['user_id' => $this->user->id],
            $data
        );

        $this->editing = false;
        $this->relationship = UserRelationship::where('user_id', $this->user->id)->first();
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function cancel()
    {
        $this->editing = false;
    }

    public function render()
    {
        $canView = $this->relationship &&
            (
                $this->relationship->visibility === 'public'
                || ($this->relationship->visibility === 'friends' && ($this->isOwner || $this->isFriend))
                || ($this->relationship->visibility === 'only_me' && $this->isOwner)
            );

        return view('livewire.relationship-section', [
            'canView' => $canView,
            'partnerName' => $this->relationship?->partner?->name,
        ]);
    }
}
