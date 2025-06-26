<div class="p-6 space-y-6">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
        Add Album
    </h2>

    @if (session()->has('message'))
        <div class="text-green-600 font-medium bg-green-50 dark:bg-green-800/20 px-4 py-2 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="create" class="space-y-4">
        <div>
            <input
                type="text"
                wire:model.defer="body"
                placeholder="Album caption or title"
                class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-2 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
            @error('body')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-xl transition"
            >
                Create Album
            </button>
        </div>
    </form>
</div>
