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
    public array $messageOffsets = []; // track pagination offsets per chat

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
            $this->openChats[$userId]['status'] = $this->openChats[$userId]['status'] === 'open' ? 'minimized' : 'open';
        } else {
            $user = User::findOrFail($userId);

            $this->openChats[$userId] = [
                'status' => 'open',
                'name' => $user->name,
                'profile_public_id' => $user->profile_public_id,
            ];

            $this->messageInputs[$userId] = '';
            $this->messageOffsets[$userId] = 0;

            $this->loadMessages($userId);
        }

        $this->dispatch('scroll-chat', userId: $userId);
    }

    public function loadMessages(int $userId)
    {
        $offset = $this->messageOffsets[$userId] ?? 0;

        $newMessages = Message::where(function ($q) use ($userId) {
                $q->where('sender_id', auth()->id())->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', auth()->id());
            })
            ->with('sender')
            ->orderByDesc('created_at')
            ->skip($offset)
            ->take(15)
            ->get()
            ->reverse()
            ->values()
            ->toArray();

        $this->messages[$userId] = array_merge($newMessages, $this->messages[$userId] ?? []);
        $this->messageOffsets[$userId] += 15;
    }

    #[On('load-older-messages')]
    public function loadOlderMessages(int $userId)
    {
        if (!isset($this->openChats[$userId])) return;

        $this->loadMessages($userId);
    }

    #[On('message-received')]
    public function addIncomingMessage(string $message, int $sender_id, string $sender_name, string $type)
    {
        if (!isset($this->messages[$sender_id])) {
            $this->messages[$sender_id] = [];
        }

        $this->messages[$sender_id][] = [
            'message' => $message,
            'type' => $type,
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

                $path = Storage::disk('cloudinary')->putFile('messages', $file);
                $url = Storage::disk('cloudinary')->url($path);
                $publicId = pathinfo($path, PATHINFO_FILENAME);

                $message = Message::create([
                    'sender_id' => auth()->id(),
                    'receiver_id' => $userId,
                    'message' => $path,
                    'type' => $type,
                    'url' => $url,
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

        $this->dispatch('scroll-chat', userId: $userId);
    }

    public function closeChat($userId)
    {
        unset($this->openChats[$userId], $this->messageInputs[$userId], $this->messages[$userId], $this->messageOffsets[$userId]);
    }

    public function render()
    {
        return view('livewire.chat-manager');
    }
}
