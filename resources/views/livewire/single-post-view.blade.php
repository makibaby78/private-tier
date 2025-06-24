<div class="max-w-xl mx-auto mt-8 p-4 bg-white shadow rounded space-y-4">
    {{-- User Info --}}
    <div class="flex items-center gap-3">
        <img src="{{ $post->user->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover" alt="">
        <div>
            <h2 class="font-bold">{{ $post->user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
        </div>
    </div>

    {{-- Post Body --}}
    <div class="text-lg text-gray-800">
        {{ $post->body }}
    </div>

    {{-- Post Image --}}
    @if ($post->media && $post->media->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($post->media as $media)
                @if ($media->type === 'image')
                    <img src="{{ $media->url }}" class="w-full rounded shadow" alt="Image post">
                @elseif ($media->type === 'video')
                    <video controls class="w-full rounded shadow">
                        <source src="{{ $media->url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @endif
            @endforeach
        </div>
    @endif

    {{-- Placeholder for reactions/comments later --}}
    <div class="text-sm text-gray-500 border-t pt-4">
        Comments, likes, and other interactions coming soon...
    </div>
</div>
