<div class="flex-1 flex flex-col h-screen">
    {{-- Header --}}
    @if (!$partnerId)
        <div class="flex-1 bg-gradient-to-br from-green-200 to-green-300 flex items-center justify-center">
            <span class="text-gray-700 text-sm text-center">Select a chat to start messaging</span>
        </div>
    @else
        <div class="flex flex-col h-full">
            <div class="px-4 py-3 border-b bg-white flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(optional(\App\Models\User::find($partnerId))->name ?? 'User') }}" class="w-10 h-10 rounded-full">
                <div>
                    <div class="font-semibold">{{ optional(\App\Models\User::find($partnerId))->name ?? 'User' }}</div>
                    <div class="text-xs text-gray-500">Online</div>
                </div>
            </div>

            {{-- Messages container --}}
            <div
                x-data
                x-init="
                    // Listen to custom event from Livewire to scroll to bottom
                    window.addEventListener('scroll-chat-to-bottom', () => {
                        const el = $refs.scroll;
                        if (!el) return;
                        el.scrollTop = el.scrollHeight;
                    });

                    // If you want auto-scroll to bottom on initial load
                    setTimeout(() => { const el = $refs.scroll; if (el) el.scrollTop = el.scrollHeight; }, 50);
                "
                class="flex-1 overflow-auto p-4"
                x-ref="scroll"
            >
                <div class="flex justify-center mb-3">
                    <button wire:click="loadMore" class="text-xs text-blue-600 hover:underline">Load more</button>
                </div>

                <div class="space-y-3">
                    @foreach ($messages as $m)
                        @php
                            $isMe    = $m['sender_id'] === auth()->id();
                            $body    = $m['message'];
                            $created = $m['created_at'];
                        @endphp

                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] px-4 py-2 rounded-2xl
                                {{ $isMe ? 'bg-blue-600 text-white' : 'bg-white border' }}">
                                
                                <div class="text-sm break-words">
                                    {{ $body }}
                                </div>

                                <div class="text-[10px] text-gray-400 mt-1 text-right">
                                    {{ \Carbon\Carbon::parse($created)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Input --}}
            <form wire:submit.prevent="connectSend({{$partnerId}})" class="bg-white p-3 border-t">
                <div class="flex gap-3">
                    <input
                        type="text"
                        wire:model.defer="messageInputs.{{ $partnerId }}"
                        placeholder="Type a message..."
                        class="flex-1 rounded-full px-4 py-2 border focus:outline-none"
                    />
                    <button type="submit" class="px-4 py-2 rounded-full bg-blue-600 text-white">Send</button>
                </div>
            </form>
        </div>
    @endif
</div>
