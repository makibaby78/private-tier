<div class="space-y-4">
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-2 rounded">
            {{ session('message') }}
        </div>
    @endif
    @forelse ($posts as $post)
        <div class="p-4 bg-white border rounded space-y-2">
            <div class="flex items-center justify-between">
                <div class="flex gap-2">
                    <x-profile-photo 
                        :path="$post->user->profile_photo_path" 
                        :alt="$post->user->name" 
                        class="rounded-full object-cover w-10 h-10" 
                        width="50" 
                        height="50" 
                    />
                    <div>
                        <h5 class="text-base font-semibold">{{ $post->user->name }}</h5>
                        <small class="text-gray-500">
                            {{ $post->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @auth
                @if ($post->user_id === auth()->id())
                <x-dropdown align="right" width="40">
                    <x-slot name="trigger">
                        <button class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-150">
                            <svg viewBox="0 0 20 20" width="20" height="20" fill="currentColor" class="xfx01vb x1lliihq x1tzjh5l x1k90msu x2h7rmj x1qfuztq" style="--color: var(--secondary-icon);"><g fill-rule="evenodd" transform="translate(-446 -350)"><path d="M458 360a2 2 0 1 1-4 0 2 2 0 0 1 4 0m6 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0m-12 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0"></path></g></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div>
                            <button 
                                wire:click="editPost({{ $post->id }})" 
                                class="hover:text-blue-600 p-2 hover:bg-gray-100 w-full text-left"
                            >
                                ✏️ Edit
                            </button>
                        </div>
                        <div>
                            <button 
                                wire:click="trashPost({{ $post->id }})" 
                                class="hover:text-red-600 p-2 hover:bg-gray-100 w-full text-left"
                            >
                                🗑️ Move to Trash
                            </button>
                        </div>
                    </x-slot>
                </x-dropdown>
                @endif
            @endauth
            </div>

            <p>{{ $post->body }}</p>

            @if ($post->image)
                <img src="{{ $post->image }}" class="w-full max-w-sm rounded shadow">
            @endif


        </div>
    @empty
        <p class="text-gray-500 italic">No posts yet.</p>
    @endforelse
</div>
