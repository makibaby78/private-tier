<div class="flex items-center justify-between border-b border-gray-200 px-4 pb-2">
    <!-- Left: Sub-navigation tabs -->
    <div class="flex space-x-6 text-sm font-medium">
        {{-- <a href="{{ route('profile.photos.index', [$user->username, 'tab' => 'tagged']) }}"
        class="{{ $photoTab === 'tagged' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-black' }} pb-2">
            Photos of You
        </a> --}}

        <a href="{{ route('profile.photos.index', ['username' => $user->username]) }}"
        class="{{ $photoTab === 'own' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-black' }} pb-2">
            Your Photos
        </a>

        <a href="{{ route('profile.photos.albums.index', ['username' => $user->username]) }}"
        class="{{ $photoTab === 'albums' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-black' }} pb-2">
            Albums
        </a>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center space-x-2">

        @auth
            @if (auth()->id() === $user->id)
                <button
                    x-data
                    x-on:click.prevent="$dispatch('open-modal', 'upload-photo')"
                    class="text-sm text-blue-600 hover:underline"
                >
                    {{ __('Add photos/video') }}
                </button>
        
                <x-modal name="upload-photo" focusable>
                    <livewire:post-form />
                </x-modal>
            @endif
        @endauth

        <div class="relative group">
            <button class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200">
                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6 10a2 2 0 114 0 2 2 0 01-4 0zm6 0a2 2 0 114 0 2 2 0 01-4 0z" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-md hidden group-hover:block z-10">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Manage Tags</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Hide Photos</a>
            </div>
        </div>
    </div>
</div>
