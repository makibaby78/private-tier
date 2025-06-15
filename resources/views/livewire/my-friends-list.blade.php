<div>
    <div class="mb-4 space-x-2">
        Friends ({{ $count }})
    </div>

    @if ($friends->count())
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" style="width: fit-content">
            @foreach ($friends as $friend)
                <div>
                    <a href="{{ route('profile.index', ['username' => $friend->username]) }}">
                        <x-profile-photo 
                            :path="$friend->profile_photo_path" 
                            :alt="$friend->name" 
                            class="rounded object-cover w-32 h-32" 
                            width="50" 
                            height="50" 
                        />
                    </a>
                    <a href="{{ route('profile.index', ['username' => $friend->username]) }}"
                       class="text-sm font-semibold">{{ $friend->name }}</a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No friends in this category.</p>
    @endif
</div>
