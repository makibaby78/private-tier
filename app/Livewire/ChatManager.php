<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ChatManager extends Component
{
    use WithFileUploads;

    public array $openChats = [];
    public array $messageInputs = [];
    public array $messages = [];
    public array $media = [];

    public function updatedMedia()
    {
        $this->validate([
            'media.*' => 'file|mimes:jpeg,png,jpg,mp4,mov,webm|max:102400', // 100MB max
        ]);
    }

    public function removeMedia($index)
    {
        unset($this->media[$index]);
        $this->media = array_values($this->media); // Re-index the array
    }

    #[On('open-chat')]
    public function openChat(int $userId)
    {
        if (isset($this->openChats[$userId])) {
            // Toggle minimized/open
            $this->openChats[$userId]['status'] = $this->openChats[$userId]['status'] === 'open' ? 'minimized' : 'open';
        } else {
            // First time open
            $user = User::findOrFail($userId);
            
            $this->openChats[$userId] = [
                'status' => 'open',
                'name' => $user->name,
                'profile_public_id' => $user->profile_public_id,
            ];

            $this->messageInputs[$userId] = '';

            $this->messages[$userId] = Message::where(function ($q) use ($userId) {
                $q->where('sender_id', auth()->id())->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', auth()->id());
            })->with('sender')->orderBy('created_at')->get()->toArray();
        }

        // Dispatch to scroll when chat is reopened
        $this->dispatch('scroll-chat', userId: $userId);
    }

    #[On('message-received')]
    public function addIncomingMessage(string $message, int $sender_id, string $sender_name)
    {
        if (!isset($this->messages[$sender_id])) {
            $this->messages[$sender_id] = [];
        }

        $this->messages[$sender_id][] = [
            'message' => $message,
            'sender_id' => $sender_id,
            'sender_name' => $sender_name,
            'created_at' => now()->toDateTimeString(),
        ];

        $this->dispatch('scroll-chat', userId: $sender_id);
    }

    public function sendMessage($userId)
    {
        $text = trim($this->messageInputs[$userId] ?? '');

        if ($text === '' && empty($this->media)) {
            return;
        }

        if (!empty($this->media)) {
            foreach ($this->media as $file) {
                $type = str($file->getMimeType())->startsWith('image') ? 'image' : 'video';

                $publicId = Storage::disk('cloudinary')->putFile('messages', $file);

                $message = Message::create([
                    'sender_id' => auth()->id(),
                    'receiver_id' => $userId,
                    'message' => $publicId,
                    'type' => $type,
                ]);
            }
        }

        if ($text != '') {

            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $userId,
                'message' => $text,
                'type' => 'text',
            ]);
        }

        $this->messages[$userId][] = $message->load('sender')->toArray();
        $this->messageInputs[$userId] = '';
        $this->media = [];

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        // Dispatch to scroll down on send
        $this->dispatch('scroll-chat', userId: $userId);
    }

    public function closeChat($userId)
    {
        unset($this->openChats[$userId], $this->messageInputs[$userId], $this->messages[$userId]);
    }

    public function render()
    {
        return view('livewire.chat-manager');
    }
}
