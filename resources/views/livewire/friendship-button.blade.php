<div class="flex gap-x-2">
    @auth
        @if (auth()->id() === $targetUser->id)
            <a href="{{ route('settings.edit') }}" class="bg-gray-700 text-white px-4 py-2 rounded inline-block">Edit Profile</a>
        @else
            @if ($status === 'friends')
                <x-dropdown>
                    <x-slot name="trigger">
                        <button class="bg-gray-300 text-black px-4 py-1 rounded">Friends</button>
                    </x-slot>
                    <x-slot name="content">
                        <button wire:click="unfriend" class="bg-white w-full">Unfriend</button>
                    </x-slot>
                </x-dropdown>
            @elseif ($status === 'sent')
                <button wire:click="cancelRequest" class="bg-yellow-500 text-white px-4 py-1 rounded">Cancel Request</button>
            @elseif ($status === 'received')
                <button wire:click="acceptRequest" class="bg-green-500 text-white px-4 py-1 rounded">Accept Request</button>
            @else
                <button wire:click="sendRequest" class="bg-blue-500 text-white px-4 py-1 rounded">Add Friend</button>
            @endif

            <button 
                x-data 
                @click="window.chatManager.call('openChat', {{ $targetUser->id }})"
                class="bg-blue-600 text-white px-4 py-1 rounded"
            >
                Message
            </button>
        @endif
    @else
        <a href="{{ route('login') }}" class="text-blue-600">Log in to add friends</a>
    @endauth
</div>
