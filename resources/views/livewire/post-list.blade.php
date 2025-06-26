<div 
    x-data="{
        init() {
            window.addEventListener('scroll', () => {
                const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 100;
                if (nearBottom && @js($hasMore)) {
                    $wire.loadMore();
                }
            });
        }
    }"
    x-init="init"
    class="space-y-4"
>
    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-2 rounded">
            {{ session('message') }}
        </div>
    @endif

    {{-- Posts --}}
    @forelse ($posts as $post)
        <x-post :post="$post" :back="$currentPath" />
    @empty
        <p class="text-gray-500 italic">No posts yet.</p>
    @endforelse

    {{-- Loading/End Message --}}
    @if ($hasMore)
        <div class="text-center text-gray-500 py-4">
            Loading more posts...
        </div>
    @else
        <div class="text-center text-gray-400 py-4">
            You’ve reached the end.
        </div>
    @endif

    {{-- Edit Post Modal --}}
    <x-ui.modal name="edit-post-modal">
        <h2 class="text-xl font-semibold mb-4">Edit Post</h2>
    
        {{-- Post Body --}}
        <textarea 
            wire:model.defer="editingBody" 
            class="w-full border p-2 rounded" 
            rows="4"
            placeholder="What's on your mind?"
        ></textarea>
    
        @if ($editingPost?->media && $editingPost->media->count())
            <div class="mt-4 space-y-2">
                <p class="font-medium text-sm text-gray-700">Current Media:</p>
        
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($editingPost->media as $i => $media)
                        @if ($i < 4)
                            <div class="relative w-full">
                                @if ($media->type === 'image')
                                    <img src="{{ $media->url }}" class="w-full rounded shadow">
                                @elseif ($media->type === 'video')
                                    <video 
                                        controls
                                        controlsList="nodownload" 
                                        oncontextmenu="return false;"
                                        class="w-full rounded shadow"
                                    >
                                        <source src="{{ $media->url }}" type="video/mp4">
                                    </video>
                                @endif
        
                                @if ($i === 3 && $editingPost->media->count() > 4)
                                    <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center text-white text-xl font-bold rounded">
                                        +{{ $editingPost->media->count() - 4 }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    
    
        @if (!empty($newMedia))
            <div class="mt-4 space-y-2">
                <p class="font-medium text-sm text-gray-700">New Media Selected:</p>
        
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($newMedia as $i => $file)
                        @if ($i < 3)
                            <div class="relative w-full">
                                @if (str_starts_with($file->getMimeType(), 'image/'))
                                    <img src="{{ $file->temporaryUrl() }}" class="w-full rounded shadow">
                                @elseif (str_starts_with($file->getMimeType(), 'video/'))
                                    <video 
                                        controls
                                        controlsList="nodownload" 
                                        oncontextmenu="return false;" 
                                        class="w-full rounded shadow"
                                    >
                                        <source src="{{ $file->temporaryUrl() }}" type="{{ $file->getMimeType() }}">
                                    </video>
                                @endif
        
                                @if ($i === 2 && count($newMedia) > 3)
                                    <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center text-white text-xl font-bold rounded">
                                        +{{ count($newMedia) - 3 }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    
    
        {{-- File Upload Input --}}
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Media:</label>
            <input 
                type="file" 
                wire:model="newMedia" 
                multiple 
                accept="image/*,video/*"
                class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                       file:rounded file:border-0 file:text-sm file:font-semibold
                       file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
            >
            @error('newMedia.*') 
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p> 
            @enderror
        </div>
    
        {{-- Action Buttons --}}
        <div class="mt-6 flex justify-end gap-2">
            <button 
                wire:click="savePost" 
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                wire:loading.attr="disabled"
                wire:target="savePost"
            >
                <span wire:loading.remove wire:target="savePost">💾 Save</span>
                <span wire:loading wire:target="savePost">⏳ Saving...</span>
            </button>
    
            <button 
                @click="$dispatch('close-modal', { name: 'edit-post-modal' })" 
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
            >
                Cancel
            </button>
        </div>
    </x-ui.modal>
    
</div>
