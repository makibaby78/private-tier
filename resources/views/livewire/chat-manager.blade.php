<div x-data x-init="window.chatManager = $wire" class="space-y-4">
    @forelse ($openChats as $userId => $chat)
        <div class="border rounded p-2 bg-white shadow">
            <div class="flex justify-between items-center">
                <h2 class="text-sm font-bold">Chat with User {{ $userId }}</h2>
                <button wire:click="openChat({{ $userId }})" class="text-xs text-blue-500">
                    {{ $chat['status'] === 'open' ? 'Minimize' : 'Open' }}
                </button>
            </div>

            @if ($chat['status'] === 'open')
                <div class="mt-2">
                    <p class="text-sm text-gray-600">Chat content here...</p>
                </div>
            @endif
        </div>
    @empty
        <p class="text-gray-500 text-sm">No chats opened.</p>
    @endforelse
</div>
