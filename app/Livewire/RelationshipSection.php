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
    public $friends = [];

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

        $this->incomingRequest = UserRelationship::where('partner_id', $user->id)
            ->where('confirmed', false)
            ->first();

        if ($this->isOwner) {
            $this->friends = $this->user->friends();
        }
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

        $existingSince = $existing->since ? \Carbon\Carbon::parse($existing->since)->format('Y-m-d') : null;
        $dataSince = $data['since'] ?? null;
        
        if (
            $existing &&
            $existing->status === $data['status'] &&
            (int) $existing->partner_id === (int) $data['partner_id'] &&
            $existingSince === $dataSince &&
            $existing->visibility === $data['visibility']
        ) {
            $this->editing = false;
            return;
        }        
    
        if ($data['status'] === 'single') {
            if ($existing && $existing->partner_id) {
                // Remove the mirrored partner record
                UserRelationship::where('user_id', $existing->partner_id)
                    ->where('partner_id', $this->user->id)
                    ->delete();
            }

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
            ($this->relationship->confirmed || $this->isOwner) &&
            $this->relationship->canViewBy(Auth::user());
    
        return view('livewire.relationship-section', [
            'canView' => $canView,
            'partnerName' => $this->relationship?->partner?->name,
            'incomingRequest' => $this->incomingRequest,
        ]);
    }
}
