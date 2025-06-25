@php
    $mediaList = $post->media->map(fn($m) => [
        'url' => $m->url,
        'type' => $m->type,
    ])->values();
@endphp
<div class="px-2 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto mt-8 p-4 bg-white shadow rounded space-y-4">
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

        {{-- Post Media --}}
        @if ($post->media && $post->media->count())
        <div
            x-data="{
                open: false,
                currentIndex: 0,
                mediaItems: {{ $mediaList }},
                show(index) {
                    this.currentIndex = index;
                    this.open = true;
                },
                next() {
                    this.currentIndex = (this.currentIndex + 1) % this.mediaItems.length;
                },
                prev() {
                    this.currentIndex = (this.currentIndex - 1 + this.mediaItems.length) % this.mediaItems.length;
                }
            }"
        >
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($post->media as $index => $media)
                    @if ($media->type === 'image')
                        <img
                            src="{{ $media->url }}"
                            class="object-contain cursor-pointer w-full"
                            style="max-height: 470px;"
                            alt="Image post"
                            @click="show({{ $index }})"
                        >
                    @elseif ($media->type === 'video')
                        <div
                            class="relative cursor-pointer"
                            @click="show({{ $index }})"
                        >
                            <video
                                class="w-full rounded shadow"
                                muted
                                preload="metadata"
                            >
                                <source src="{{ $media->url }}#t=0.1" type="video/mp4">
                            </video>

                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Modal component with swipe support -->
            <x-ui.modal-media />
        </div>
        @endif

        {{-- Placeholder for reactions/comments later --}}
        <div class="text-sm text-gray-500 border-t pt-4">
            Comments, likes, and other interactions coming soon...
        </div>
    </div>
</div>