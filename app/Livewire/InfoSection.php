<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserContact;
use Illuminate\Support\Facades\Auth;

class InfoSection extends Component
{
    public $contacts = [];
    public $type = 'phone', $value, $label, $visibility = 'public';
    public $showForm = false;
    public $editingId = null;
    public $isOwner;
    public $user;
    public $isFriend;

    public function mount($user, $isOwner, $isFriend = false)
    {
        $this->user = $user;
        $this->isOwner = $isOwner;
        $this->isFriend = $isFriend;
        $this->loadContacts();
    }

    public function loadContacts()
    {
        $this->contacts = $this->user->contacts()
            ->get()
            ->filter(function ($contact) {
                return $contact->visibility === 'public'
                    || ($contact->visibility === 'friends' && ($this->isOwner || $this->isFriend))
                    || ($contact->visibility === 'only_me' && $this->isOwner);
            });
    }

    public function save()
    {
        $validated = $this->validate([
            'type' => 'required|in:phone,email,website,other',
            'value' => 'required|string',
            'label' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,friends,only_me',
        ]);

        $data = [
            'user_id' => $this->user->id,
            ...$validated,
        ];

        if ($this->editingId) {
            UserContact::findOrFail($this->editingId)->update($data);
        } else {
            UserContact::create($data);
        }

        $this->resetForm();
        $this->loadContacts();
    }

    public function edit($id)
    {
        $contact = UserContact::findOrFail($id);
        $this->editingId = $id;
        $this->type = $contact->type;
        $this->value = $contact->value;
        $this->label = $contact->label; 
        $this->visibility = $contact->visibility;
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset(['type', 'value', 'label', 'visibility', 'editingId', 'showForm']);
        $this->type = 'phone';
        $this->visibility = 'public';
    }

    public function render()
    {
        return view('livewire.info-section');
    }
}
