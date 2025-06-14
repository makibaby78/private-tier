<div>
    @auth
        @if (auth()->id() === $targetUser->id)
            <a href="{{ route('settings.edit') }}" class="bg-gray-700 text-white px-4 py-2 rounded inline-block">Edit Profile</a>
        @else
            @if ($status === 'friends')
                <button wire:click="unfriend" class="bg-red-500 text-white px-4 py-1 rounded">Unfriend</button>
            @elseif ($status === 'sent')
                <button wire:click="cancelRequest" class="bg-yellow-500 text-white px-4 py-1 rounded">Cancel Request</button>
            @elseif ($status === 'received')
                <button wire:click="acceptRequest" class="bg-green-500 text-white px-4 py-1 rounded">Accept Request</button>
            @else
                <button wire:click="sendRequest" class="bg-blue-500 text-white px-4 py-1 rounded">Add Friend</button>
            @endif
        @endif
    @else
        <a href="{{ route('login') }}" class="text-blue-600">Log in to add friends</a>
    @endauth
</div>
