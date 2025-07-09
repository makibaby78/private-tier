@props(['post', 'back' => null])

<div class="bg-white dark:bg-gray-800 sm:rounded-lg overflow-hidden space-y-4">
    <div class="px-4 pt-4">
        <div class="flex items-center justify-between">
            <div class="flex gap-2 items-center">
                <a href="{{ route('profile.index', $post->user->username) }}">
                    <x-profile-photo 
                        :path="$post->user->profile_public_id" 
                        :alt="$post->user->name" 
                        class="rounded-full object-cover w-10 h-10" 
                        width="40" 
                        height="40" 
                    />
                </a>
                <div>
                    <a href="{{ route('profile.index', $post->user->username) }}">

                        <h5 class="text-base font-semibold dark:text-white inline">
                            {{ $post->user->name }}
                        </h5>

                        @if ($post->isProfilePicture())
                            <p class="text-gray-500 text-base inline">updated their profile picture.</p>
                        @endif

                    </a>
                    <p>
                        <small class="text-gray-500">
                            {{ $post->created_at->diffForHumans() }}
                        </small>
                    </p>
                </div>
            </div>

            @auth
                @if ($post->user_id === auth()->id())
                    <x-dropdown align="right" width="32">
                        <x-slot name="trigger">
                            <button class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-150">
                                <svg viewBox="0 0 20 20" width="20" height="20" fill="currentColor">
                                    <g fill-rule="evenodd" transform="translate(-446 -350)">
                                        <path d="M458 360a2 2 0 1 1-4 0 2 2 0 0 1 4 0m6 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0m-12 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0"></path>
                                    </g>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div>
                                <button 
                                    wire:click="openEditModal({{ $post->id }})"
                                    class="hover:text-blue-600 dark:text-white p-2 hover:bg-gray-100 w-full text-left text-sm"
                                >
                                    ✏️ Edit
                                </button>
                            </div>
                            <div>
                                <button 
                                    wire:click="trashPost({{ $post->id }})" 
                                    class="hover:text-red-600 dark:text-white p-2 hover:bg-gray-100 w-full text-left text-sm"
                                >
                                    🗑️ Move to Trash
                                </button>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @endif
            @endauth
        </div>
        <p class="text-gray-800 dark:text-white">{{ $post->body }}</p>
        
        @if ($post->media && $post->media->isNotEmpty())
            <div class="border-t border-gray-300 pt-2 mt-2">
                <a
                    class="text-xs font-bold text-blue-600 hover:underline"
                    href="{{ route('profile.posts.show', ['username' => $post->user->username, 'post' => $post->id]) }}?from=posts&back={{ $back }}"
                >
                    View Post
                </a>
            </div>
        @endif

    </div>

    <div class="w-full h-full flex gap-2 relative">
        @foreach ($post->media ?? [] as $i => $item)
            @if ($i < 3)
                <div class="relative w-full">
                    @if ($item['type'] === 'image')
                        <a href="{{ route('profile.media.index', [$post->user->username, $item->id]) }}?from=posts&back={{ $back }}">
                            <img src="{{ $item['url'] }}" class="object-contain w-full h-auto">
                        </a>
                    @elseif ($item['type'] === 'video')
                        <a href="{{ route('profile.media.index', [$post->user->username, $item->id]) }}?from=posts&back={{ $back }}">
                            <video
                                class="w-full shadow cursor-pointer"
                                style="max-height: 470px;"
                                muted
                                controls
                                controlsList="nodownload"
                                oncontextmenu="return false;"
                            >
                                <source src="{{ $item['url'] }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </a>
                    @endif
    
                    @if ($i === 2 && count($post->media) > 3)
                        <a 
                            href="{{ route('profile.posts.show', ['username' => $post->user->username, 'post' => $post->id]) }}" 
                            class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center text-white text-xl font-bold rounded"
                        >
                            +{{ count($post->media) - 3 }}
                        </a>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
