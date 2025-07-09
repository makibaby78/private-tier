@php
    $mediaList = $post->media->map(fn($m) => [
        'url' => $m->url,
        'type' => $m->type,
    ])->values();
@endphp
<div class="px-2 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto mt-8 bg-white dark:bg-gray-800 shadow rounded space-y-4">

        <div class="pt-4 px-4">
            <div class="flex items-center gap-3">
                <img src="{{ $post->user->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover" alt="">
                <div>
                    <h2 class="dark:text-white font-bold">{{ $post->user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-white">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <div>
                <p class="text-gray-800 dark:text-white pt-2 text-base">{{ $post->body }}</p>
            </div>
        </div>

        @if ($post->media && $post->media->count())
            @php
                $count = $post->media->count();
                $gridCols = match (true) {
                    $count === 1 => 'grid-cols-1',
                    $count === 2 => 'grid-cols-2',
                    $count === 3 => 'grid-cols-3',
                    default => 'grid-cols-3 lg:grid-cols-4',
                };
            @endphp
        
            <div class="grid {{ $gridCols }} gap-2">
                @foreach ($post->media as $index => $media)
                    @if ($media->type === 'image')
                        <img
                            src="{{ $media->url }}"
                            loading="lazy"
                            class="object-cover w-full h-full max-h-[470px]"
                            alt="Post image"
                        >
                    @elseif ($media->type === 'video')

                        <video
                            src="{{ $media->url }}" 
                            class="w-full max-h-[470px] object-cover"
                            muted
                            preload="metadata"
                            controls
                            controlsList="nodownload"
                            oncontextmenu="return false;"
                        >
                            <source src="{{ $media->url }}" type="video/mp4">
                        </video>

                    @endif
                @endforeach
            </div>
        @endif

        <div class="p-4 pt-1">
            <div class="text-sm text-gray-500 border-t pt-4">
                Comments, likes, and other interactions coming soon...
            </div>
        </div>
    </div>
</div>