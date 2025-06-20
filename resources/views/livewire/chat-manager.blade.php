<div 
    x-data 
    x-init="window.chatManager = $wire"
    class="fixed bottom-0 right-0 flex flex-row-reverse gap-3 p-4 z-50"
>
    @foreach ($openChats as $userId => $chat)
        <div 
            x-data="{
                scrollToBottom() {
                    $nextTick(() => {
                        const el = $refs['chatBody' + {{ $userId }}];
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                }
            }"
            x-init="scrollToBottom()"
            @scroll-chat.window="if ($event.detail.userId === {{ $userId }}) scrollToBottom()"
            class="w-72 bg-white shadow-lg rounded-t-lg border border-gray-300"
        >
            {{-- Header --}}
            <div class="flex items-center justify-between bg-blue-600 text-white px-3 py-2 rounded-t-lg">
                <span class="text-sm font-semibold">Chat with User {{ $userId }}</span>
                <div class="flex gap-2 items-center">
                    <button wire:click="openChat({{ $userId }})" class="hover:text-gray-200" title="Minimize">–</button>
                    <button wire:click="closeChat({{ $userId }})" class="hover:text-red-200" title="Close">×</button>
                </div>
            </div>

            {{-- Body --}}
            @if ($chat['status'] === 'open')
                <div class="p-2">
                    <div 
                        x-ref="chatBody{{ $userId }}" 
                        class="h-40 overflow-y-auto border rounded mb-2 p-1 text-sm text-gray-700 space-y-1"
                        id="messages"
                    >
                        @foreach ($messages[$userId] ?? [] as $msg)
                            <div class="{{ $msg['sender_id'] === auth()->id() ? 'text-right' : 'text-left' }}">
                                <div class="inline-block px-2 py-1 rounded {{ $msg['sender_id'] === auth()->id() ? 'bg-blue-100' : 'bg-gray-100' }}">
                                    <span class="block text-xs text-gray-500">{{ $msg['sender']['name'] ?? 'User' }}</span>
                                    <span>{{ $msg['message'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Message form --}}
                    <form wire:submit.prevent="sendMessage({{ $userId }})" class="flex gap-1 mt-2">
                        <input 
                            type="text"
                            wire:model.defer="messageInputs.{{ $userId }}"
                            placeholder="Type a message..."
                            class="flex-1 px-2 py-1 border rounded text-sm"
                        />
                        <button 
                            type="submit"
                            class="bg-blue-600 text-white px-2 py-1 rounded text-sm hover:bg-blue-700"
                        >
                            Send
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @endforeach
</div>
