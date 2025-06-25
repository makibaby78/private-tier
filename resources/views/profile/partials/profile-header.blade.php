<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
    <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="flex items-center justify-between flex-col md:flex-row gap-y-4 gap-x-2">
            <div class="flex flex-col md:flex-row items-center gap-y-4 gap-x-2">
                
                <x-profile-photo 
                    :path="$user->profile_photo_path" 
                    :alt="$user->name" 
                    class="rounded object-cover w-32 h-32" 
                    width="50" 
                    height="50" 
                />

                <h1 class="text-xl font-extrabold leading-none tracking-tight text-gray-900 md:text-3xl lg:text-4xl dark:text-white">{{ $user->name }}</h1>

            </div>
            
            <livewire:friendship-button :targetUser="$user" />
        </div>

        @php
            $activeTab = $activeTab ?? 'posts'; // fallback if not set
        @endphp

        <nav class="border-b border-gray-200 text-sm font-semibold">
            <ul class="flex space-x-6 px-4 overflow-x-auto">
                <li>
                    <a href="{{ route('profile.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block {{ $activeTab === 'posts' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-black' }}">
                        Posts
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.about.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block {{ $activeTab === 'about' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-black' }}">
                        About
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.friends.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block {{ $activeTab === 'friends' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-black' }}">
                        Friends
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.photos.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block {{ $activeTab === 'photos' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-black' }}">
                        Photos
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.videos.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block {{ $activeTab === 'videos' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-black' }}">
                        Videos
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.reels.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block {{ $activeTab === 'reels' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-black' }}">
                        Reels
                    </a>
                </li>
                {{-- <li class="relative group">
                    <button class="py-4 inline-block text-gray-600 hover:text-black flex items-center focus:outline-none">
                        More
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="absolute hidden group-hover:block bg-white border rounded shadow-md mt-1 p-2 z-10">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Activity</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Likes</a>
                    </div>
                </li> --}}
            </ul>
        </nav>

    </div>
</div>