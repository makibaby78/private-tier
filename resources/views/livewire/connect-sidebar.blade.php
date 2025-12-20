<div class="border-r border-gray-300 flex flex-col min-w-[250px] max-w-sm resize-x overflow-auto">
    <!-- Search -->
    <div class="flex items-center gap-3 px-3 py-3 border-b border-gray-100">
        <button class="p-2 rounded-md hover:bg-gray-100">
            <x-icons.hamburger />
        </button>

        <input type="text" wire:model.live="search" placeholder="Search"
            class="w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" />
    </div>

    <!-- Chats -->
    <div class="overflow-y-auto">
        @forelse ($chats as $chat)
            @php
                $userId = auth()->id();
                $partner = $chat->partner($userId);
            @endphp

            <div wire:click="openConnect({{ $partner->id }})"
                class="flex items-center gap-3 p-3 hover:bg-gray-200 cursor-pointer">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($partner->name) }}" class="w-10 h-10 rounded-full">
                <div class="flex-1">
                    <div class="flex justify-between">
                        <span class="font-semibold">{{ $partner->name }}</span>
                        <span class="text-xs text-gray-500">{{ $chat->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-gray-600 truncate">
                        {{ $chat->lastMessage->message }}
                    </p>
                </div>
            </div>
        @empty
            <div class="p-4 text-gray-500 text-sm text-center">
                No chats yet
            </div>
        @endforelse
    </div>
</div>
