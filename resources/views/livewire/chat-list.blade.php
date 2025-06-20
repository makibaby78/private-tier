<div class="p-2 space-y-1">
    <h2 class="px-2 text-lg font-bold">Chats</h2>

    @foreach ($friendsWithLastMessage as $friend)
        <button 
            x-data 
            @click="window.chatManager.call('openChat', {{ $friend['id'] }})"
            class="w-full"
        >
            <div class="p-2 flex items-center space-x-3 rounded hover:bg-gray-100">
                <img src="{{ $friend['photo'] }}" alt="{{ $friend['name'] }}"
                    class="w-10 h-10 rounded-full object-cover">

                <div class="flex-1">
                    <div class="text-left font-semibold">{{ $friend['name'] }}</div>
                    <div class="text-left text-sm text-gray-500 truncate">{{ $friend['last_message'] }} {{ $friend['last_time'] ?? '' }}</div>
                </div>
            </div>
        </button>
    @endforeach
</div>
