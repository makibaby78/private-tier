<div class="space-y-4">
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-2 rounded">
            {{ session('message') }}
        </div>
    @endif
    @forelse ($posts as $post)
        <x-post :post="$post" />
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
