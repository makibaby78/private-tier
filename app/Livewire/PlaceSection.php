<?php

namespace App\Livewire;

use App\Models\UserPlace;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Country;
use App\Models\City;

class PlaceSection extends Component
{
    public $user;
    public $isOwner;
    public $city_id;
    public $deleteId = null;
    public $region;
    public $countryId;
    public $type = 'current_city';
    public $visibility = 'public';
    public $showForm = false;
    public $editingId = null;

    public $countries = [];
    public $cities = [];

    public $visiblePlaces = [];

    public function mount($user, $isOwner = false)
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
        $this->countries = Country::orderBy('name')->get();
        $this->loadPlaces();
    }

    public function updatedCountryId($value)
    {
        $this->cities = $this->loadCities($value);
    }

    public function loadCities($countryId)
    {   
        return $countryId
            ? City::where('country_id', $countryId)->orderBy('name')->get()
            : collect();
    }

    public function confirmDelete($id)
    {
        if (!$this->isOwner) {
            return;
        }

        $this->deleteId = $id;
    }

    public function save()
    {
        $this->validate([
            'type' => 'required|in:current_city,hometown',
            'city_id' => 'nullable|exists:cities,id',
            'region' => 'nullable|string|max:255',
            'countryId' => 'nullable|exists:countries,id',
            'visibility' => 'required|in:public,friends,only_me',
        ]);

        $this->user->places()->create([
            'type' => $this->type,
            'city_id' => $this->city_id,
            'region' => $this->region,
            'country_id' => $this->countryId,
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

        $this->cities = City::where('country_id', $place->country_id)
        ->orderBy('name')
        ->get();

        $this->city_id = $place->city_id;
        $this->region = $place->region;
        $this->countryId = $place->country_id;
        $this->visibility = $place->visibility;
        $this->showForm = true;
    }

    public function update() 
    { 
        $this->validate([ 
            'type' => 'required|in:current_city,hometown', 
            'city_id' => 'nullable|exists:cities,id', 
            'region' => 'nullable|string|max:255', 
            'countryId' => 'nullable|exists:countries,id', 
            'visibility' => 'required|in:public,friends,only_me', 
        ]); 
        
        $place = $this->user->places()->findOrFail($this->editingId); 

        $place->update([ 
            'type' => $this->type, 
            'city_id' => $this->city_id, 
            'region' => $this->region, 
            'country_id' => $this->countryId, 
            'visibility' => $this->visibility, 
        ]); 
        
        $this->resetForm(); $this->loadPlaces(); 
    }

    public function deletePlace()
    {
        if (!$this->isOwner || !$this->deleteId) {
            return;
        }

        $place = $this->user->places()->find($this->deleteId);

        if ($place) {
            $place->delete();
        }

        $this->deleteId = null;
        $this->loadPlaces();
    }

    public function resetForm()
    {
        $this->reset([
            'city_id', 'region', 'countryId', 'type',
            'visibility', 'editingId', 'showForm'
        ]);

        $this->type = 'current_city';
        $this->visibility = 'public';
    }

    public function loadPlaces()
    {
        $viewer = Auth::user();
        $places = $this->user->places()->with(['city', 'country'])->get();

        $this->visiblePlaces = $places->filter(function ($place) use ($viewer) {

            if ($place->visibility === 'public') return true;

            if ($place->visibility === 'friends') {
                if ($this->isOwner) {
                    return true;
                }
    
                if (!$viewer) {
                    return false;
                }
    
                return $viewer->friends()
                    ->where('users.id', $this->user->id)
                    ->exists();
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
