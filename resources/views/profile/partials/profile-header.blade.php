<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
    <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="border-b border-gray-200 flex items-center justify-between flex-col pb-6 md:flex-row gap-y-4 gap-x-2">
            <div class="flex flex-col md:flex-row items-center gap-y-4 gap-x-2">
                
                <x-dropdown align="left" width="40">
                    <x-slot name="trigger">
                        <x-profile-photo 
                            :path="$user->profile_public_id" 
                            :alt="$user->name" 
                            class="rounded object-cover w-32 h-32" 
                            width="50" 
                            height="50" 
                        />
                    </x-slot>

                    <x-slot name="content">
                        @isset($profile_post_id)
                            <a href="{{ route('profile.posts.show', ['username' => $user->username, 'post' => $profile_post_id]) }}?from=posts&back={{ $user->username }}"
                                class="cursor-pointer hover:text-blue-600 dark:text-white p-2 hover:bg-gray-100 w-full text-left text-sm block">
                                See profile picture
                            </a>
                        @endisset

                        @if (auth()->id() === $user->id)
                            <div>
                                <button 
                                    x-on:click.prevent="$dispatch('open-modal', 'upload-profile')"
                                    class="hover:text-blue-600 dark:text-white p-2 hover:bg-gray-100 w-full text-left text-sm"
                                >
                                    Choose profile picture
                                </button>
                            </div>
                        @endif
                    </x-slot>
                </x-dropdown>

                <h1 class="text-xl font-extrabold leading-none tracking-tight text-gray-900 md:text-3xl lg:text-4xl dark:text-white">{{ $user->name }}</h1>

            </div>
            
            <livewire:friendship-button :targetUser="$user" />
        </div>

        @php
            $activeTab = $activeTab ?? 'posts'; // fallback if not set
        @endphp

        <nav class="text-sm font-semibold">
            <ul class="flex space-x-6 px-4 overflow-x-auto">
                <li>
                    <a href="{{ route('profile.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block dark:text-white {{ $activeTab === 'posts' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-400' }}">
                        Posts
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.about.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block dark:text-white {{ $activeTab === 'about' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-400' }}">
                        About
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.friends.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block dark:text-white {{ $activeTab === 'friends' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-400' }}">
                        Friends
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.photos.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block dark:text-white {{ $activeTab === 'photos' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-400' }}">
                        Photos
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.videos.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block dark:text-white {{ $activeTab === 'videos' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-400' }}">
                        Videos
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.reels.index', ['username' => $user->username]) }}"
                    class="py-4 inline-block dark:text-white {{ $activeTab === 'reels' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-400' }}">
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


    <x-modal name="upload-profile" focusable>
                                    
        <button
            x-on:click="$dispatch('close')"
            class="absolute rounded-full top-0 right-0 p-2 text-sm
                text-gray-600 hover:text-black hover:bg-gray-300
                dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700"
        >
            {{ __('X') }}
        </button>

        <div class="px-6 pt-6">
            <h2 class="text-lg font-extrabold leading-none tracking-tight text-gray-900 md:text-xl lg:text-2xl dark:text-white">
                Upload
            </h2>
        </div>

        <div class="space-y-2 bg-white p-6 dark:bg-gray-800 shadow-sm sm:rounded-lg">
            @if (session()->has('message'))
                <div class="bg-green-200 text-green-800 p-2 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <form 
            x-data="{
                previewUrl: null,
                updatePreview(event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = e => this.previewUrl = e.target.result;
                        reader.readAsDataURL(file);
                    } else {
                        this.previewUrl = null;
                    }
                }
            }"
            action="{{ route('profile.update-picture', ['username' => auth()->user()->username]) }}" 
            method="POST" 
            enctype="multipart/form-data"
        >
            @csrf

            <div class="mb-4">
                <textarea id="body" name="body" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Caption">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
    
            <!-- File input: only image -->
            <div class="mb-4">
                <input 
                    id="media" 
                    name="media[]" 
                    type="file" 
                    accept="image/*" 
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    @change="updatePreview"
                    required
                >
                @error('media')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('media.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
    
            <!-- Live image preview -->
            <div class="mb-4" x-show="previewUrl" x-transition>
                <p class="text-sm text-gray-600 mb-2">Image Preview:</p>
                <img :src="previewUrl" alt="Preview" class="w-32 h-32 object-cover rounded border">
            </div>
    
            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Update Profile Picture
            </button>
        </form>

        </div>

    </x-modal>
</div>