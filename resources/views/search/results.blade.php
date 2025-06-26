<x-app-layout>
    <div class="max-w-3xl mx-auto py-6">
        <h2 class="text-lg font-semibold">Search results for: "{{ $query }}"</h2>

        @if ($users->count())
            @foreach ($users as $user)
                <div class="max-w-7xl mx-auto mt-4">
                    <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">

                        <div class="flex items-center gap-y-4 gap-x-2 mb-4">
                            <div>
                                <a href="{{ route('profile.index', $user->username) }}">
                                    <x-profile-photo 
                                        :path="$user->profile_public_id" 
                                        :alt="$user->name" 
                                        class="rounded-full object-cover w-10 h-10" 
                                        width="30" 
                                        height="30" 
                                    />
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('profile.index', $user->username) }}" class="hover:underline">
                                    <strong class="text-base">{{ $user->name }}</strong>
                                </a>
                                <p class="text-sm text-gray-500">Person</p>
                            </div>
                        </div>

                        <button>{{ $user->friendship_label }}</button>
                    </div>
                </div>
            @endforeach
        @endif

        @if ($posts->count())
            <h3 class="mt-4 font-medium">Posts</h3>
            <ul>
                @foreach ($posts as $post)
                    <li>{{ $post->title }}</li>
                @endforeach
            </ul>
        @endif

        @if ($users->isEmpty() && $posts->isEmpty())
            <p class="mt-4 text-gray-500">No results found.</p>
        @endif
    </div>
</x-app-layout>
