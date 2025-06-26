<div class="space-y-4">
    <div>
        Add Album
    </div>

    @if (session()->has('message'))
        <div class="text-green-600 font-medium">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="create" class="flex flex-col gap-2 max-w-md">
        <input
            type="text"
            wire:model="body"
            placeholder="Album caption or title"
            class="border px-3 py-2 rounded"
        >
        @error('body') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Create Album
        </button>
    </form>
</div>
