<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserWorkExperience;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class WorkSection extends Component
{
    public string $position = '';
    public string $company = '';
    public ?string $location = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public bool $is_current = false;
    public string $visibility = 'public';
    public bool $showForm = false;
    public ?int $editingId = null;
    public User $user;
    public bool $isOwner = false;


    public function mount(User $user, bool $isOwner = false)
    {
        $this->user = $user;
        $this->isOwner = $isOwner;

        $this->experiences = $user->workExperiences()->latest('start_date')->get();
    }

    public function edit(int $id)
    {
        $work = UserWorkExperience::findOrFail($id);
        $this->editingId = $id;

        $this->position = $work->position;
        $this->company = $work->company;
        $this->location = $work->location;
        $this->start_date = $work->start_date?->format('Y-m-d');
        $this->end_date = $work->end_date?->format('Y-m-d');
        $this->is_current = $work->is_current;
        $this->visibility = $work->visibility;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate([
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'visibility' => 'in:public,friends,only_me',
        ]);

        $work = UserWorkExperience::findOrFail($this->editingId);

        $work->update([
            'position' => $this->position,
            'company' => $this->company,
            'location' => $this->location,
            'start_date' => $this->start_date,
            'end_date' => $this->is_current ? null : $this->end_date,
            'is_current' => $this->is_current,
            'visibility' => $this->visibility,
        ]);

        $this->reset([
            'position', 'company', 'location', 'start_date',
            'end_date', 'is_current', 'visibility', 'showForm', 'editingId'
        ]);
    }

    public function cancelEdit()
    {
        $this->reset([
            'position', 'company', 'location', 'start_date',
            'end_date', 'is_current', 'visibility', 'showForm', 'editingId'
        ]);
    }

    public function save(): void
    {
        $this->validate([
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'visibility' => 'in:public,friends,only_me',
        ]);

        UserWorkExperience::create([
            'user_id' => Auth::id(),
            'position' => $this->position,
            'company' => $this->company,
            'location' => $this->location,
            'start_date' => $this->start_date,
            'end_date' => $this->is_current ? null : $this->end_date,
            'is_current' => $this->is_current,
            'visibility' => $this->visibility,
        ]);

        $this->reset([
            'position', 'company', 'location',
            'start_date', 'end_date',
            'is_current', 'visibility', 'showForm'
        ]);
    }

    public function getExperiencesProperty()
    {
        return UserWorkExperience::where('user_id', Auth::id())->latest()->get();
    }

    public function render()
    {
        return view('livewire.work-section');
    }
}
