<div>
    <div class="mb-4 space-x-2">
        @foreach (['all' => 'All', 'best' => 'Best Friends', 'close' => 'Close Friends'] as $key => $label)
            <button wire:click="setFilter('{{ $key }}')"
                class="px-3 py-1 rounded {{ $filter === $key ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if ($friends->count())
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($friends as $friend)
                <div class="bg-white p-4 rounded shadow text-center">
                    <img src="{{ $friend->profile_photo_url ?? 'https://via.placeholder.com/64' }}"
                         class="w-16 h-16 rounded-full mx-auto mb-2">
                    <h3 class="font-semibold">{{ $friend->name }}</h3>
                    <a href="{{ route('profile.index', ['username' => $friend->username]) }}"
                       class="text-sm text-blue-600">View Profile</a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No friends in this category.</p>
    @endif
</div>
