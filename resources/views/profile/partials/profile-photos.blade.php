<div class="p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <div class="mb-4 space-x-2">
        Photos
    </div>

    @if ($photos->count())
        <div class="grid grid-cols-3 gap-4 w-full">
            @foreach ($photos as $photo)
                <div>
                    <div class="w-full sm:max-w-xs md:max-w-sm lg:max-w-md aspect-square rounded overflow-hidden">
                        <a href="{{ route('profile.photos.media.index', [$user->username, $photo->id]) }}?from=photos&back={{ $user->username }}">
                            <div class="aspect-square overflow-hidden rounded">
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