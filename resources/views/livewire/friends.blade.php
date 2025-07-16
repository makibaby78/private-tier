<div
    x-data="{
        observe() {
            let observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        $wire.loadMore();
                    }
                });
            }, { threshold: 1.0 });

            observer.observe(this.$refs.loadMoreTrigger);
        }
    }"
    x-init="observe()"
    class="mx-auto dark:text-white"
>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Friends</h2>

        <div class="flex md:gap-x-4 gap-x-2">
            <div class="relative flex items-center w-full border-red-100">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-icons.magnifying-glass class="text-gray-500" />
                </span>
            
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search Friends"
                    class="max-w-md w-full bg-transparent placeholder:text-slate-400 text-slate-700 dark:text-white text-sm border border-slate-200 rounded-md pl-10 pr-3 py-1 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
                >
            </div>

            @if (auth()->id() === $user->id)
                <div>
                    <a href="{{ route('friends.requests.index') }}" class="text-blue-600 font-bold hover:underline whitespace-nowrap">
                        Friend Requests
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="flex space-x-4 border-b pb-2 mb-4 overflow-x-auto">
        @if (auth()->id() === $user->id)
            @foreach (['All friends', 'Birthdays'] as $tab)
                <button
                    wire:click="setActiveTab('{{ $tab }}')"
                    class="text-sm font-medium whitespace-nowrap px-3 py-2 border-b-2 transition
                        {{ $activeTab === $tab ? 'text-blue-600 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-600' }}"
                >
                    {{ $tab }}
                </button>
            @endforeach
        @else
            <button 
                class="text-sm font-medium whitespace-nowrap px-3 py-2 border-b-2 transition text-blue-600 border-blue-600"
            >
                Friends
            </button>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($friends as $friend)
            <div class="flex items-center space-x-4 p-3 rounded shadow-md bg-white dark:bg-gray-900">
                <a href="{{ route('profile.index', $friend->username) }}">
                    <x-profile-photo 
                        :path="$friend->profile_public_id" 
                        :alt="$friend->name" 
                        class="rounded object-cover w-10 h-10" 
                        width="50" 
                        height="50" 
                    />
                </a>

                <div class="flex-1">
                    <a href="{{ route('profile.index', $friend->username) }}">
                        <p class="font-semibold">{{ $friend->name }}</p>
                    </a>
                    <p class="text-sm text-gray-500">{{ $friend->mutual_friends }} mutual friends</p>
                </div>
                <button class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6 10a2 2 0 114 0 2 2 0 01-4 0zm4-6a2 2 0 110 4 2 2 0 010-4zm0 12a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </button>
            </div>
        @empty
            <p class="text-gray-500">No friends found.</p>
        @endforelse
    </div>

    <!-- Trigger to load more -->
    <div x-ref="loadMoreTrigger" class="h-6 my-4"></div>
</div>
