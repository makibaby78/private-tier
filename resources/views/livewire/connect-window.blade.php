<div class="flex-1 flex flex-col h-screen">
    {{-- Header --}}
    @if (!$partnerId)
        <div class="flex-1 bg-gradient-to-br from-green-200 to-green-300 flex items-center justify-center">
            <span class="text-gray-700 text-sm text-center">Select a chat to start messaging</span>
        </div>
    @else
        <div class="flex flex-col h-full">
            <div class="px-4 py-3 border-b bg-white flex items-center gap-3">

                <x-profile-photo 
                    :path="$partner->profile_public_id" 
                    :alt="$partner->name" 
                    class="rounded-full object-cover w-10 h-10" 
                    width="50" 
                    height="50" 
                />

                <div>
                    <div class="font-semibold">{{ $partner->name }}</div>
                    <div class="text-xs text-gray-500">Online</div>
                </div>
            </div>

            {{-- Messages container --}}
            <div
                x-data
                x-init="
                    window.addEventListener('scroll-chat-to-bottom', () => {
                        $nextTick(() => {
                            const el = $refs.scroll;
                            if (!el) return;

                            // small delay so media/images finish layout
                            setTimeout(() => {
                                el.scrollTo({
                                    top: el.scrollHeight,
                                    behavior: 'smooth'
                                });
                            }, 50);
                        });
                    });

                    // initial scroll
                    $nextTick(() => {
                        const el = $refs.scroll;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                "
                class="flex-1 overflow-auto p-4"
                x-ref="scroll"
            >
                @if($hasMoreMessages)
                    <div class="text-center py-2">
                        <button
                            wire:click="loadMore"
                            wire:loading.attr="disabled"
                            class="text-sm text-gray-500 hover:underline"
                        >
                            Load more messages
                        </button>
                    </div>
                @endif

                <div class="space-y-3">
                    @foreach ($messages as $m)
                        @php
                            $isMe    = $m['sender_id'] === auth()->id();
                            $body    = $m['message'];
                            $created = $m['created_at'];
                            $isMedia = $m['type'] === 'media_group';
                            $count   = count($m['media'] ?? []);
                        @endphp

                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} mb-2">
                            <div class="max-w-[72%]
                                {{ $isMe ? 'bg-blue-600 text-white' : 'bg-white border' }}
                                rounded-2xl overflow-hidden shadow-sm">

                                {{-- 🖼️ MEDIA GROUP --}}
                                @if ($isMedia)
                                    <div class="
                                        grid gap-[2px]
                                        {{ $count === 1 ? 'grid-cols-1' : 'grid-cols-2' }}
                                    ">
                                        @foreach ($m['media'] as $media)
                                            @if ($media['type'] === 'image')
                                                <img
                                                    src="{{ asset($media['url']) }}"
                                                    class="w-full h-full object-cover max-h-[280px]"
                                                    loading="lazy"
                                                />
                                            @elseif ($media['type'] === 'video')
                                                <video controls class="w-full max-h-[280px] object-cover">
                                                    <source src="{{ asset($media['url']) }}">
                                                </video>
                                            @elseif ($media['type'] === 'audio')
                                                <div class="p-3">
                                                    <audio controls class="w-full">
                                                        <source src="{{ asset($media['url']) }}">
                                                    </audio>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    {{-- Caption --}}
                                    @if (!empty($body))
                                        <div class="px-3 py-2 text-sm break-words">
                                            {{ $body }}
                                        </div>
                                    @endif
                                @else
                                    {{-- 💬 TEXT MESSAGE --}}
                                    <div class="px-4 py-2 text-sm break-words">
                                        {{ $body }}
                                    </div>
                                @endif

                                {{-- ⏱ Timestamp --}}
                                <div class="px-2 pb-1 text-[10px] text-right opacity-70">
                                    {{ \Carbon\Carbon::parse($created)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- Input --}}
            <form wire:submit.prevent="connectSend({{$partnerId}})" class="bg-white p-3 border-t">
                <div class="flex items-center gap-2">

                    <label class="flex items-center justify-center cursor-pointer p-2 hover:bg-gray-100 rounded-full transition">
                        <x-icons.paperclip class="w-6 h-6 text-gray-500"/>
                        <input type="file" accept="image/*,video/*" class="hidden" multiple wire:model="media">
                    </label>

                    <input
                        type="text"
                        wire:model.defer="messageInputs.{{ $partnerId }}"
                        placeholder="Type a message..."
                        class="flex-1 rounded-full px-4 py-2 border focus:outline-none"
                        id="connect-send"
                    />
                    <button type="submit" class="px-4 py-2 rounded-full bg-blue-600 text-white">Send</button>
                </div>
            </form>
        </div>
    @endif
</div>
