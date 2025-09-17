<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ConnectWindow extends Component
{
    public ?int $partnerId = null;
    public \Illuminate\Support\Collection $messages;
    public int $perPage = 30;
    
    public function mount()
    {
        $this->messages = collect();
    }    

    #[On('connect-selected')]
    public function loadChat(int $userId): void
    {
        // set partner id
        $this->partnerId = (int) $userId;
    
        // defensive: ensure partnerId present and not the same as the authenticated user
        $me = auth()->id();
        if (! $this->partnerId || $this->partnerId === $me) {
            // clear messages if bad partner or self
            $this->messages = collect();
            // optional: notify front-end (you can remove if you don't want a notification)
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => $this->partnerId === $me ? 'Cannot open chat with yourself.' : 'Invalid chat partner.'
            ]);
            return;
        }
    
        // ensure default page size
        $this->perPage ??= 30;
    
        $partner = $this->partnerId;
    
        // fetch the latest N messages between me <-> partner, then reverse so oldest is first
        $msgs = \App\Models\Message::where(function ($q) use ($me, $partner) {
                $q->where('sender_id', $me)->where('receiver_id', $partner);
            })->orWhere(function ($q) use ($me, $partner) {
                $q->where('sender_id', $partner)->where('receiver_id', $me);
            })
            ->with('sender')                // eager-load sender for display (avatar/name)
            ->latest('created_at')         // newest first
            ->take($this->perPage)
            ->get()
            ->reverse()                    // now oldest first
            ->values();
    
        // mark partner->me messages as read (only those not already read)
        \App\Models\Message::where('sender_id', $partner)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    
        // set messages for the view
        $this->messages = $msgs;
    
        // optionally notify sidebar (if you have a ChatSidebar Livewire component that should refresh)
        // remove this line if you don't use it:
        $this->dispatch('chat-loaded', userId: $partner)->to(\App\Livewire\ConnectSidebar::class);
    }

    public function render()
    {
        return view('livewire.connect-window');
    }
}
