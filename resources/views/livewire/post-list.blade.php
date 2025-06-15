<div class="space-y-4">
    @forelse ($posts as $post)
        <div class="p-4 bg-white border rounded space-y-2">
            <p>{{ $post->body }}</p>

            @if ($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" class="w-full max-w-sm rounded shadow">
            @endif

            <small class="text-gray-500">
                Posted {{ $post->created_at->diffForHumans() }}
            </small>
        </div>
    @empty
        <p class="text-gray-500 italic">No posts yet.</p>
    @endforelse
</div>
