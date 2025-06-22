@props(['post'])

<div class="p-4 bg-white border rounded-md space-y-2">
    <div class="flex items-center justify-between">
        <div class="flex gap-2">
            <a href="{{ route('profile.index', $post->user->username) }}">
                <x-profile-photo 
                    :path="$post->user->profile_photo_path" 
                    :alt="$post->user->name" 
                    class="rounded-full object-cover w-10 h-10" 
                    width="50" 
                    height="50" 
                />
            </a>
            <div>
                <a href="{{ route('profile.index', $post->user->username) }}">
                    <h5 class="text-base font-semibold">{{ $post->user->name }}</h5>
                </a>
                <small class="text-gray-500">
                    {{ $post->created_at->diffForHumans() }}
                </small>
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
                                class="hover:text-blue-600 p-2 hover:bg-gray-100 w-full text-left text-sm"
                            >
                                ✏️ Edit
                            </button>
                        </div>
                        <div>
                            <button 
                                wire:click="trashPost({{ $post->id }})" 
                                class="hover:text-red-600 p-2 hover:bg-gray-100 w-full text-left text-sm"
                            >
                                🗑️ Move to Trash
                            </button>
                        </div>
                    </x-slot>
                </x-dropdown>
            @endif
        @endauth
    </div>

    <p>{{ $post->body }}</p>

    @if ($post->video)
        <video class="w-full max-w-sm rounded shadow" controls>
            <source src="{{ $post->video }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    @elseif ($post->image)
        <img src="{{ $post->image }}" class="w-full max-w-sm rounded shadow" alt="{{ $post->body }}">
    @endif
</div>
