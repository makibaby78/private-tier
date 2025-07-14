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
    public $incomingRequest;

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

        // Find incoming request to this user
        $this->incomingRequest = UserRelationship::where('partner_id', $user->id)
            ->where('confirmed', false)
            ->first();
    }

    public function updatedStatus($value)
    {
        if ($value === 'single') {
            $this->partner_id = null;
            $this->since = null;
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

        $existing = $this->relationship;

        if ($data['status'] === 'single') {
            // Remove partner's record if needed
            if ($existing && $existing->partner_id) {
                UserRelationship::where('user_id', $existing->partner_id)
                    ->where('partner_id', $this->user->id)
                    ->delete();
            }

            // Save own record as single
            UserRelationship::updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'status' => $data['status'],
                    'since' => $data['since'],
                    'visibility' => $data['visibility'],
                    'partner_id' => null,
                    'confirmed' => true,
                ]
            );

        } else {
            // Save new relationship request
            $data['confirmed'] = $data['partner_id'] ? false : true;

            UserRelationship::updateOrCreate(
                ['user_id' => $this->user->id],
                $data
            );
        }

        $this->editing = false;
        $this->relationship = UserRelationship::where('user_id', $this->user->id)->first();
        $this->incomingRequest = UserRelationship::where('partner_id', $this->user->id)
            ->where('confirmed', false)
            ->first();
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function cancel()
    {
        $this->editing = false;
    }

    public function accept()
    {
        if ($this->incomingRequest) {

            $this->incomingRequest->update(['confirmed' => true]);

            UserRelationship::updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'status' => $this->incomingRequest->status,
                    'partner_id' => $this->incomingRequest->user_id,
                    'since' => $this->incomingRequest->since,
                    'visibility' => $this->incomingRequest->visibility,
                    'confirmed' => true,
                ]
            );

            $this->mount($this->user, $this->isOwner, $this->isFriend);
        }
    }

    public function decline()
    {
        if ($this->incomingRequest) {
            $this->incomingRequest->delete();
            $this->incomingRequest = null;
        }
    }

    public function render()
    {
        $canView = $this->relationship &&
            (
                $this->relationship->confirmed ||
                $this->isOwner
            ) &&
            (
                $this->relationship->visibility === 'public' ||
                ($this->relationship->visibility === 'friends' && ($this->isOwner || $this->isFriend)) ||
                ($this->relationship->visibility === 'only_me' && $this->isOwner)
            );     

        return view('livewire.relationship-section', [
            'canView' => $canView,
            'partnerName' => $this->relationship?->partner?->name,
            'incomingRequest' => $this->incomingRequest,
        ]);
    }
}
