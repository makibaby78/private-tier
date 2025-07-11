<?php

namespace App\Livewire;

use App\Models\UserPlace;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PlaceSection extends Component
{
    public $user;
    public $isOwner;
    public $city;
    public $region;
    public $country;
    public $type = 'current_city';
    public $visibility = 'public';
    public $showForm = false;
    public $editingId = null;

    public $visiblePlaces = [];

    public function mount($user, $isOwner = false)
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
        $this->loadPlaces();
    }

    public function save()
    {
        $this->validate([
            'type' => 'required|in:current_city,hometown',
            'city' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,friends,only_me',
        ]);

        $this->user->places()->create([
            'type' => $this->type,
            'city' => $this->city,
            'region' => $this->region,
            'country' => $this->country,
            'visibility' => $this->visibility,
        ]);

        $this->resetForm();
        $this->loadPlaces();
    }

    public function edit($id)
    {
        $place = $this->user->places()->findOrFail($id);
        $this->editingId = $place->id;
        $this->type = $place->type;
        $this->city = $place->city;
        $this->region = $place->region;
        $this->country = $place->country;
        $this->visibility = $place->visibility;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate([
            'type' => 'required|in:current_city,hometown',
            'city' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,friends,only_me',
        ]);

        $place = $this->user->places()->findOrFail($this->editingId);
        $place->update([
            'type' => $this->type,
            'city' => $this->city,
            'region' => $this->region,
            'country' => $this->country,
            'visibility' => $this->visibility,
        ]);

        $this->resetForm();
        $this->loadPlaces();
    }

    public function resetForm()
    {
        $this->reset([
            'city', 'region', 'country', 'type',
            'visibility', 'editingId', 'showForm'
        ]);

        $this->type = 'current_city';
        $this->visibility = 'public';
    }

    public function loadPlaces()
    {
        $viewer = Auth::user();
        $places = $this->user->places;

        $this->visiblePlaces = $places->filter(function ($place) use ($viewer) {
            if ($place->visibility === 'public') return true;
            if ($place->visibility === 'friends') {
                return $this->isOwner || ($viewer && $viewer->friends()->contains($this->user));
            }
            if ($place->visibility === 'only_me') return $this->isOwner;
            return false;
        })->values();
    }

    public function render()
    {
        return view('livewire.place-section');
    }
}
