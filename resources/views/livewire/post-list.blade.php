<div class="space-y-4">
    @forelse ($posts as $post)
        <div class="p-4 bg-white border rounded space-y-2">
            <div class="flex gap-2">
                <x-profile-photo 
                    :path="$post->user->profile_photo_path" 
                    :alt="$post->user->name" 
                    class="rounded-full object-cover w-10 h-10" 
                    width="50" 
                    height="50" 
                />
                <div>
                    <h5 class="text-base font-semibold">{{ $post->user->name }}</h5>
                    <small class="text-gray-500">
                        {{ $post->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>

            <p>{{ $post->body }}</p>

            @if ($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" class="w-full max-w-sm rounded shadow">
            @endif

        </div>
    @empty
        <p class="text-gray-500 italic">No posts yet.</p>
    @endforelse
</div>
