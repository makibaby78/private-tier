<div>
    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 mt-4 px-4">
        @forelse ($photos as $photo)
            <a href="{{ route('profile.photos.media.index', [$user->username, $photo->id]) }}?from=photos&back={{ $currentPath }}">
                <div class="aspect-square overflow-hidden rounded">
                    <img
                        src="{{ $photo->url }}"
                        alt="User Photo"
                        class="object-cover w-full h-full"
                        loading="lazy"
                    />
                </div>
            </a>
        @empty
            <p class="text-sm text-gray-500 col-span-3">No photos uploaded yet.</p>
        @endforelse
    </div>

    <div class="mt-4 px-4">
        {{ $photos->links() }}
    </div>
</div>
