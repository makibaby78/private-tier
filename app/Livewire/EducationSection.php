<?php

namespace App\Livewire;

use App\Models\UserEducation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EducationSection extends Component
{
    public $user;
    public $isOwner;
    public $level = 'college';
    public $school;
    public $degree;
    public $start_date;
    public $end_date;
    public $is_current = false;
    public $visibility = 'public';
    public $showForm = false;
    public $editingId = null;

    public $visibleEducations = [];
    public $canView = true;

    public function mount($user, $isOwner = false)
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
        $this->loadEducations();
    }

    public function save()
    {
        $this->validate([
            'level' => 'required|string',
            'school' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'visibility' => 'required|in:public,friends,only_me',
        ]);

        $this->user->educations()->create([
            'level' => $this->level,
            'school' => $this->school,
            'degree' => $this->level === 'college' ? $this->degree : null,
            'start_date' => $this->start_date,
            'end_date' => $this->is_current ? null : $this->end_date,
            'is_current' => $this->is_current,
            'visibility' => $this->visibility,
        ]);

        $this->resetForm();
        $this->loadEducations();
    }

    public function edit($id)
    {
        $edu = $this->user->educations()->findOrFail($id);

        $this->editingId = $edu->id;
        $this->level = $edu->level;
        $this->school = $edu->school;
        $this->degree = $edu->degree;
        $this->start_date = $edu->start_date;
        $this->end_date = $edu->end_date;
        $this->is_current = $edu->is_current;
        $this->visibility = $edu->visibility;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate([
            'level' => 'required|string',
            'school' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'visibility' => 'required|in:public,friends,only_me',
        ]);

        $edu = $this->user->educations()->findOrFail($this->editingId);

        $edu->update([
            'level' => $this->level,
            'school' => $this->school,
            'degree' => $this->level === 'college' ? $this->degree : null,
            'start_date' => $this->start_date,
            'end_date' => $this->is_current ? null : $this->end_date,
            'is_current' => $this->is_current,
            'visibility' => $this->visibility,
        ]);

        $this->resetForm();
        $this->loadEducations();
    }

    public function resetForm()
    {
        $this->reset([
            'level', 'school', 'degree', 'start_date', 'end_date',
            'is_current', 'visibility', 'editingId', 'showForm',
        ]);

        $this->level = 'college';
        $this->visibility = 'public';
        $this->is_current = false;
    }

    public function loadEducations()
    {
        $viewer = Auth::user();
        $educations = $this->user->educations;

        $this->visibleEducations = $educations->filter(function ($edu) use ($viewer) {
            if ($edu->visibility === 'public') {
                return true;
            }

            if ($edu->visibility === 'friends') {
                return $this->isOwner || ($viewer && $viewer->friends()->contains($this->user));
            }

            if ($edu->visibility === 'only_me') {
                return $this->isOwner;
            }

            return false;
        })->values();

        $this->canView = $this->visibleEducations->isNotEmpty();
    }

    public function render()
    {
        return view('livewire.education-section');
    }
}