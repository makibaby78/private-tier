<div class="p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <div class="mb-4 space-x-2">
        Friends ({{ $count }})
    </div>

    @if ($friends->count())
        <div class="grid grid-cols-3 gap-4 w-full">
            @foreach ($friends as $friend)
                <div>
                    <div class="w-full sm:max-w-xs md:max-w-sm lg:max-w-md aspect-square rounded overflow-hidden">
                        <a href="{{ route('profile.index', ['username' => $friend->username]) }}">
                            <x-profile-photo 
                                :path="$friend->profile_public_id" 
                                :alt="$friend->name" 
                                class="object-cover w-full h-full" 
                                width="100" 
                                height="100" 
                            />
                        </a>
                    </div>
                    <a href="{{ route('profile.index', ['username' => $friend->username]) }}"
                       class="text-sm font-semibold">{{ $friend->name }}</a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No friends in this category.</p>
    @endif
</div>
