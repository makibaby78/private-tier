<div class="p-2 space-y-1">
    <h2 class="px-2 text-lg font-bold dark:text-white">Chats</h2>

    @foreach ($friendsWithLastMessage as $friend)
        <button 
            x-data 
            @click="
                window.chatManager.call('openChat', {{ $friend['id'] }});
                $wire.markAsRead({{ $friend['id'] }})
            "
            class="w-full"
        >
            <div 
                class="p-2 flex items-center space-x-3 rounded hover:bg-gray-100 {{ $friend['is_unread'] ? 'bg-blue-50' : '' }}"
            >
                <x-profile-photo 
                    :path="$friend['profile_public_id']" 
                    :alt="$friend['name']" 
                    class="rounded-full object-cover w-6 h-6" 
                    width="30" 
                    height="30" 
                />

                <div class="flex-1">
                    <div class="text-left font-semibold {{ $friend['is_unread'] ? 'text-blue-600' : '' }}">
                        {{ $friend['name'] }}
                    </div>
                    <div class="text-left text-xs text-gray-500 truncate">
                        {{ Str::limit($friend['last_message'], 23) }} {{ $friend['last_time'] ?? '' }}
                    </div>
                </div>

                @if ($friend['is_unread'])
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                @endif
            </div>
        </button>
    @endforeach
</div>
