<x-app-layout>
    <div class="bg-black text-white flex justify-center items-start">
        <div class="flex w-full items-center justify-center flex-col md:flex-row">

{{-- Left: Media Preview with Arrows --}}
<div class="relative w-full flex items-center justify-center">

    @if (request('from'))
        {{-- Previous Arrow --}}
        @if ($prevId)
            <a href="{{ route('profile.photos.media.index', [$user->username, $prevId]) }}?from={{ request('from') }}"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl z-50 hover:text-gray-300">
                &larr;
            </a>
        @endif
    @endif

    {{-- Media --}}
    @if ($media->type === 'image')
        <img src="{{ $media->url }}" alt="Photo"
             class="w-full object-contain rounded h-[calc(60vh-32px)] md:h-[calc(100vh-64px)]">
    @elseif ($media->type === 'video')
        <video src="{{ $media->url }}" controls
               class="w-full bg-black rounded h-[calc(60vh-32px)] md:h-[calc(100vh-64px)]"></video>
    @endif

    {{-- Next Arrow --}}
    @if (request('from'))
        @if ($nextId)
            <a href="{{ route('profile.photos.media.index', [$user->username, $nextId]) }}?from={{ request('from') }}"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl z-50 hover:text-gray-300">
                &rarr;
            </a>
        @endif
    @endif
</div>


            {{-- Right: Conditional Panel --}}
            <div class="w-full md:w-[400px] h-[calc(40vh-32px)] md:h-[calc(100vh-64px)] bg-white text-black p-4 space-y-4" >
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center text-sm text-blue-500 hover:underline">
                    ← Back
                </a>
                 
                @if ($media->post->media->count() > 1)
                    <div class="border-t border-b border-gray-300 py-2 my-2">
                        <a
                            class="text-xs font-bold text-blue-600 hover:underline"
                            href="{{ route('profile.posts.show', [$media->post->user->username, $media->post->id]) }}"
                        >
                            View Post
                        </a>
                    </div>
                @endif

                <div class="flex items-center space-x-3">
                    <img src="{{ $media->post->user->profile_photo_url }}" alt="avatar" class="w-10 h-10 rounded-full">
                    <div>
                        <h2 class="font-semibold">{{ $media->post->user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $media->created_at->format('F j, Y') }}</p>
                    </div>
                </div>

                @if ($media->post->media->count() === 1)
                    <div class="text-sm">
                        {!! nl2br(e($media->post->body)) !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
