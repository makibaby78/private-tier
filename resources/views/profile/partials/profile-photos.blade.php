<div class="p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <div class="mb-4 space-x-2 dark:text-white flex justify-between">
        
        <p class="font-bold">Photos</p>

        @if ($photos->count() > 9)
            <a href="{{ route('profile.photos.index', [$user->username]) }}">
                See all photos
            </a>
        @endif

    </div>

    @if ($photos->count())
        <div class="grid grid-cols-3 gap-1 w-full rounded-lg overflow-hidden">
            @foreach ($photos as $photo)
                <div>
                    <div class="w-full sm:max-w-xs md:max-w-sm lg:max-w-md aspect-square">
                        <a href="{{ route('profile.media.index', [$user->username, $photo->id]) }}?from=photos&back={{ $user->username }}">
                            <div class="aspect-square">
                                <img
                                    src="{{ $photo->url }}"
                                    alt="User Photo"
                                    class="object-cover w-full h-full"
                                    loading="lazy"
                                />
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No photos in this category.</p>
    @endif
</div>