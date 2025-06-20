<div class="flex gap-x-2">
    @auth
        @if (auth()->id() === $targetUser->id)
            <a href="{{ route('settings.edit') }}" class="bg-gray-700 text-white px-4 py-2 rounded inline-block">Edit Profile</a>
        @else
            @if ($status === 'friends')
                <x-dropdown width="40">
                    <x-slot name="trigger">
                        <button class="bg-gray-300 text-black p-2 rounded flex items-center gap-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 4.5-1.8 4.5-4.5S14.7 3 12 3 7.5 4.8 7.5 7.5 9.3 12 12 12zm0 2c-3 0-9 1.5-9 4.5V21h18v-2.5c0-3-6-4.5-9-4.5z"/>
                            </svg>
                            Friends
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <button wire:click="unfriend" class="p-2 flex items-center gap-x-1 rounded hover:bg-gray-100 w-full">

                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 4.5-1.8 4.5-4.5S14.7 3 12 3 7.5 4.8 7.5 7.5 9.3 12 12 12zm0 2c-3 0-9 1.5-9 4.5V21h18v-2.5c0-3-6-4.5-9-4.5z"/>
                            </svg>

                            Unfriend

                        </button>
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
                class="bg-blue-600 text-white p-2 rounded flex items-center gap-x-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" viewBox="0 0 512 512">
                    <path d="M256 0C114.62 0 0 110.18 0 246.06c0 77.47 37.25 146.12 95.69 191.43V512l88.06-48.25c22.56 6.19 46.51 9.63 72.25 9.63 141.38 0 256-110.18 256-246.06S397.38 0 256 0zm29.75 314.94-57.94-62.13-122.5 62.13 142.25-151.31 59.31 62.13 119.06-62.13-140.18 151.31z"/>
                </svg>
                Message
            </button>
        @endif
    @else
        <a href="{{ route('login') }}" class="text-blue-600">Log in to add friends</a>
    @endauth
</div>
