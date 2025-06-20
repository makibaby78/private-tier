<div class="space-y-4 mb-4 bg-white p-6 dark:bg-gray-800 shadow-sm sm:rounded-lg">
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-2 rounded">
            {{ session('message') }}
        </div>
    @endif

    <textarea wire:model="body" placeholder="What's on your mind?" class="w-full p-2 border-transparent bg-gray-100 rounded"></textarea>
    <input type="file" wire:model="image" class="w-full">
    
    @if ($image)
        <div>
            <p class="text-sm text-gray-500">Preview:</p>
            <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded" alt="Preview">
        </div>
    @endif

    <button wire:click="save" class="bg-blue-600 text-white px-4 py-2 rounded">Post</button>
</div>
