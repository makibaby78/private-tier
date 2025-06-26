<x-app-layout>

    @include('profile.partials.profile-header')

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4">
        <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg space-y-4">
            Album
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4 space-y-4">
        <div class="flex items-center justify-between p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg space-y-4">
            <div>
                <h1 class="text-2xl font-bold">{{ $album->body }}</h1>
                <p class="text-sm text-gray-600">{{ $mediaItems->count() }} Items</p>
            </div>
            <button class="text-gray-600 hover:text-black">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01" />
                </svg>
            </button>
        </div>
    
        {{-- Media Grid --}}
        <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg space-y-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($mediaItems as $media)
                <a href="{{ route('profile.photos.media.index', [$user->username, $media->id]) }}?from={{ request('from') }}&back={{ $back }}">
                    <div class="relative group">
                        <img src="{{ $media->url }}" alt="Media"
                            class="w-full object-cover rounded-md border border-gray-300">
        
                        {{-- Edit Icon on hover --}}
                        @if ($isOwnProfile)
                            <button class="absolute top-1 right-1 bg-black/60 hover:bg-black/80 p-1 rounded-full text-white hidden group-hover:block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536M9 11l6 6M12 4v4m0 8v4m-6-6h4m8 0h-4"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    

</x-app-layout>