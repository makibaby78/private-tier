<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Message;
use App\Models\Conversation;
use App\Livewire\ConnectSidebar;
use Illuminate\Support\Collection;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ConnectWindow extends Component
{
    use WithFileUploads;
    
    public ?int $partnerId = null;
    public Collection $messages;
    public int $perPage = 30;
    public array $media = [];
    public array $messageInputs = [];
    
    public function mount()
    {
        $this->messages = collect();
    }    

    public function connectSend(int $userId): void
    {
        $text = trim($this->messageInputs[$userId] ?? '');

        if ($text === '' && empty($this->media)) {
            return;
        }

        $authUser = auth()->user();

        if ($authUser->id === $userId) {
            return;
        }

        $userOne = min($authUser->id, $userId);
        $userTwo = max($authUser->id, $userId);

        $conversation = Conversation::firstOrCreate(
            [
                'user_one_id' => $userOne,
                'user_two_id' => $userTwo,
            ],
            [
                'last_message_at' => now(),
            ]
        );

        DB::transaction(function () use ($conversation, $authUser, $userId, $text, &$message) {

            if (!empty($this->media)) {
                $message = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id'       => $authUser->id,
                    'receiver_id'     => $userId,
                    'message'         => $text,
                    'type'            => 'media_group',
                ]);

                foreach ($this->media as $file) {
                    $type = str($file->getMimeType())->startsWith('image')
                        ? 'image'
                        : (str($file->getMimeType())->startsWith('audio') ? 'audio' : 'video');

                    $path = Storage::disk('cloudinary')->putFile('messages', $file);
                    $url  = Storage::disk('cloudinary')->url($path);

                    $message->media()->create([
                        'type' => $type,
                        'url'  => $url,
                    ]);
                }
            } else {
                $message = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id'       => $authUser->id,
                    'receiver_id'     => $userId,
                    'message'         => $text,
                    'type'            => 'text',
                ]);
            }

            $conversation->update([
                'last_message_at' => $message->created_at,
                'last_message_id' => $message->id,
            ]);
        });

        $message->load('media');

        $this->messages->push([
            'id'          => $message->id,
            'message'     => $message->message,
            'type'        => $message->type,
            'sender_id'   => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'created_at'  => $message->created_at->toISOString(),
            'sender' => [
                'id'   => $authUser->id,
                'name' => $authUser->name,
            ],
            'media' => $message->media->map(fn ($media) => [
                'id'      => $media->id,
                'type'    => $media->type,
                'url'     => $media->url,
                'caption' => $media->caption,
            ])->values(),
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        $this->media = [];
        unset($this->messageInputs[$userId]);
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
            return;
        }
    
        // ensure default page size
        $this->perPage ??= 30;
    
        $partner = $this->partnerId;
    
        // fetch the latest N messages between me <-> partner, then reverse so oldest is first
        $msgs = Message::where(function ($q) use ($me, $partner) {
                $q->where('sender_id', $me)->where('receiver_id', $partner);
            })->orWhere(function ($q) use ($me, $partner) {
                $q->where('sender_id', $partner)->where('receiver_id', $me);
            })
            ->with(['sender','media:id,message_id,type,url,caption',])
            ->latest('created_at')         // newest first
            ->take($this->perPage)
            ->get()
            ->reverse()                    // now oldest first
            ->values();
    
        // mark partner->me messages as read (only those not already read)
        Message::where('sender_id', $partner)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    
        // set messages for the view
        $this->messages = $msgs->map(fn ($msg) => [
            'id'         => $msg->id,
            'message'    => $msg->message,
            'type'       => $msg->type,
            'sender_id'  => $msg->sender_id,
            'receiver_id'=> $msg->receiver_id,
            'created_at'  => $msg->created_at->toISOString(),
            'sender' => [
                'id'   => $msg->sender->id,
                'name' => $msg->sender->name,
            ],

            'media' => $msg->media->map(fn ($media) => [
                'id'      => $media->id,
                'type'    => $media->type,   // image | video | audio
                'url'     => $media->url,
                'caption' => $media->caption,
            ])->values(),
        ]);

        $this->dispatch('chat-loaded', userId: $partner)->to(ConnectSidebar::class);
    }

    #[On('message-received')]
    public function connectIncomingMessage(
        int $id,
        string $message,
        int $sender_id,
        string $sender_name,
        string $type,
        array $media = [],
    ): void {

        if ($this->messages->contains('id', $id)) {
            return;
        }

        if ($sender_id !== $this->partnerId) {
            return;
        }

        $this->messages->push([
            'id'         => $id,
            'message'    => $message,
            'type'       => $type,
            'sender_id'  => $sender_id,
            'receiver_id'=> auth()->id(),
            'created_at' => now(),
            'sender' => [
                'id'   => $sender_id,
                'name' => $sender_name,
            ],
            'media' => $media,
        ]);

        Message::where('sender_id', $sender_id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

    }

    public function render()
    {
        return view('livewire.connect-window');
    }
}
