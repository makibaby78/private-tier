<x-app-layout>

    @include('profile.partials.profile-header')

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4">
        <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg space-y-4">
            @include('profile.photos.partials.photos-header', [
                'photoTab' => $photoTab ?? 'tagged',
                'user' => $user
            ])

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">

                <!-- Create Album -->
                <div 
                    class="flex flex-col space-y-2 w-full"
                >
                    <button
                        x-data
                        x-on:click.prevent="$dispatch('open-modal', 'album-create')"
                        class="aspect-square w-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center rounded-md"
                    >
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    <div class="text-sm text-black font-medium text-left">Create album</div>
                </div>

                <!-- Album List -->
                @foreach ($albums as $album)
                    <div class="flex flex-col w-full">
                        <div class="relative aspect-square w-full">
                            @if($album->media->count() > 0)
                                <img src="{{ asset('storage/' . $album->media->first()->url) }}"
                                    class="object-cover w-full h-full rounded-md">
                            @else
                                <div class="w-full h-full bg-gray-200 rounded-md flex items-center justify-center text-gray-500">
                                    No media
                                </div>
                            @endif

                            <!-- 3-dot menu (optional) -->
                            <div class="absolute top-1 right-1">
                                <button class="text-white bg-black/50 rounded-full p-1 hover:bg-black/70">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-black font-semibold truncate">{{ $album->body ?? 'Untitled album' }}</div>
                        <div class="text-xs text-gray-600">{{ $album->media->count() }} Items</div>
                    </div>
                @endforeach

            </div>

        <x-modal name="album-create" focusable>

            <button
                x-on:click="$dispatch('close')"
                class="absolute rounded-full top-0 right-0 p-2 text-sm
                    text-gray-600 hover:text-black hover:bg-gray-300
                    dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700"
            >
                {{ __('X') }}
            </button>

            <livewire:album-create />

        </x-modal>

        </div>
    </div>
</x-app-layout>