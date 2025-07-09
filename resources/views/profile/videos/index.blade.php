<x-app-layout>

    @include('profile.partials.profile-header')
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4">
        <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg ">
            <h2 class="dark:text-white pb-2 font-bold">Videos</h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($videos as $video)

                    <a href="{{ route('profile.media.index', [$user->username, $video->id]) }}?from=photos&back={{ $user->username . '/videos' }}">
                        <x-video-thumbnail location="posts" :publicid="$video->public_id" :path="$video->path" />
                    </a>

                @endforeach
            </div>            
            
        </div>
    </div>

</x-app-layout>