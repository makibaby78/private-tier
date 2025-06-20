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
                                wire:click="openEditModal({{ $post->id }})"
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


    <x-ui.modal name="edit-post-modal">
        <h2 class="text-xl font-semibold mb-4">Edit Post</h2>

        <textarea wire:model.defer="editingBody" class="w-full border p-2 rounded"></textarea>

        @if ($editingPost?->image)
            <div class="mt-4">
                <p class="font-medium text-sm text-gray-700 mb-2">Current Image:</p>

                {{-- Show preview --}}
                @if ($newImage)
                    <img src="{{ $newImage->temporaryUrl() }}" class="w-full max-w-xs rounded shadow">
                @else
                    <img src="{{ $editingPost->image }}" class="w-full max-w-xs rounded shadow">
                @endif

                <input type="file" wire:model="newImage" class="mt-2">
            </div>
        @endif

        <div class="mt-4 flex justify-end gap-2">
            <button 
                wire:click="savePost" 
                class="bg-blue-600 text-white px-4 py-2 rounded"
                wire:loading.attr="disabled"
                wire:target="savePost"
            >
                <span wire:loading.remove wire:target="savePost">💾 Save</span>
                <span wire:loading wire:target="savePost">⏳ Saving...</span>
            </button>

            <button @click="$dispatch('close-modal', { name: 'edit-post-modal' })" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
        </div>
    </x-ui.modal>



</div>
