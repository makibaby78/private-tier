<div 
    x-data 
    x-init="window.chatManager = $wire"
    class="fixed bottom-0 right-0 flex flex-row-reverse gap-3 px-4 z-50"
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
            class="w-80 bg-white shadow-lg rounded-t-lg border border-gray-300"
        >
            {{-- Header --}}
            <div class="flex items-center gap-x-2 justify-between bg-blue-600 text-white px-3 py-2 rounded-t-lg">
                <div class="flex items-center gap-x-2">

                    <x-profile-photo 
                        :path="$chat['profile_public_id']" 
                        :alt="$chat['name']" 
                        class="rounded-full object-cover w-6 h-6" 
                        width="30" 
                        height="30" 
                    />

                    <span class="text-sm font-semibold">{{ $chat['name'] }}</span>

                </div>
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
                        class="h-80 border rounded mb-2 p-1 text-sm text-gray-700 flex flex-col justify-between"
                        id="messages"
                        @scroll.passive="
                        if ($el.scrollTop === 0) {
                            window.chatManager.$dispatch('load-older-messages', { userId: {{ $userId }} });
                        }"
                    >
                        <div class="overflow-y-auto space-y-1">
                            @foreach ($messages[$userId] ?? [] as $msg)
                                <div class="{{ $msg['sender_id'] === auth()->id() ? 'text-right' : 'text-left' }}">
                                    <div 
                                        class="px-2 py-1 rounded inline-block align-top max-w-[75%] break-words text-left
                                            {{ $msg['sender_id'] === auth()->id() ? 'bg-blue-100' : 'bg-gray-100' }}"
                                    >
                                        @if ($msg['type'] === 'text')
                                            <span>{{ str($msg['message'])->limit(500) }}</span>
                            
                                        @elseif ($msg['type'] === 'image')

                                            <x-cloudinary::image :public-id="$msg['message']" class="rounded max-w-full max-h-60" />
                            
                                        @elseif ($msg['type'] === 'video')
                                        <x-cloudinary::video :public-id="$msg['message']" width="300" height="200" controls />
                                        @else
                                            <span class="italic text-gray-500">Unsupported message type.</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            @foreach ($media as $index => $file)
                                <div class="relative group">
                                    <button 
                                        type="button" 
                                        wire:click="removeMedia({{ $index }})"
                                        class="absolute top-1 right-1 bg-white text-red-500 rounded-full w-6 h-6 flex items-center justify-center shadow hover:bg-red-100"
                                        title="Remove"
                                    >
                                        &times;
                                    </button>
                    
                                    @if (str($file->getMimeType())->startsWith('image'))
                                        <img src="{{ $file->temporaryUrl() }}" class="w-full m-2 h-10 object-cover rounded shadow">
                                    @elseif (str($file->getMimeType())->startsWith('video'))
                                        <video controls class="w-full m-2 h-20 object-cover rounded shadow">
                                            <source src="{{ $file->temporaryUrl() }}" type="{{ $file->getMimeType() }}">
                                        </video>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    </div>

                    {{-- Message form --}}
                    <form wire:submit.prevent="sendMessage({{ $userId }})">
                        <div class="">

                            <div class="flex gap-1 mt-2">

                                <div class="flex items-center justify-center ">
                                    <label 
                                        class="w-6 h-6 rounded-md bg-blue-400 hover:bg-blue-500 flex items-center justify-center cursor-pointer transition duration-200"
                                    >
                                        <!-- Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2ZM8 11l2.03 2.71 3.52-4.5L18 17H6l2-6Z" />
                                        </svg>
                                
                                        <input type="file" accept="image/*,video/*" class="hidden" multiple wire:model="media">
                                    </label>
                                </div>

                                <textarea
                                    wire:model.defer="messageInputs.{{ $userId }}"
                                    placeholder="Type a message..."
                                    rows="1"
                                    x-ref="ta"
                                    x-on:input="$refs.ta.style.height = 'auto'; $refs.ta.style.height = $refs.ta.scrollHeight + 'px';"
                                    @keydown.enter.prevent="$wire.sendMessage({{ $userId }})"
                                    class="flex-1 text-sm resize-none border rounded-md p-2 max-h-40 overflow-y-auto leading-snug"
                                    style="min-height: 2.5rem;"
                                ></textarea>
                                <div class="flex items-center justify-center">             
                                    <button 
                                        type="submit"
                                        class="bg-blue-600 text-white px-2 py-2 rounded text-sm hover:bg-blue-700"
                                    >
                                        Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    @endforeach
</div>
