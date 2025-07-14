<nav x-data="{ open: false }" class="fixed top-0 z-10 w-full bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 gap-x-1">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>
            </div>
        
        @auth
            <div class="flex items-center justify-center max-w-md w-full">
                <livewire:global-search />
            </div>
            <!-- Settings Dropdown -->
            <div class="flex items-center gap-x-2">
                <button class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </button>
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button
                            x-data
                            @click="window.Livewire.dispatch('refresh-chat-list')"
                            class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="black" viewBox="0 0 512 512">
                                <path d="M256 0C114.62 0 0 110.18 0 246.06c0 77.47 37.25 146.12 95.69 191.43V512l88.06-48.25c22.56 6.19 46.51 9.63 72.25 9.63 141.38 0 256-110.18 256-246.06S397.38 0 256 0zm29.75 314.94-57.94-62.13-122.5 62.13 142.25-151.31 59.31 62.13 119.06-62.13-140.18 151.31z"/>
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <livewire:chat-list />
                    </x-slot>
                </x-dropdown>
                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">                            

                            <x-profile-photo 
                                :path="Auth::user()->profile_public_id" 
                                :alt="Auth::user()->name" 
                                class="rounded-full object-cover w-10 h-10 max-w-none" 
                                width="50" 
                                height="50" 
                            />
                            <div class="ms-1">
                                <x-icons.messenger />
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.index', ['username' => Auth::user()->username])">
                            <div class="flex gap-x-2 items-center">
                                <x-profile-photo 
                                    :path="Auth::user()->profile_public_id" 
                                    :alt="Auth::user()->name" 
                                    class="rounded-full object-cover w-8 h-8 max-w-none" 
                                    width="40" 
                                    height="40" 
                                />
                                <p class="font-bold">{{ Auth::user()->name }}</p>
                            </div>
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('friends.requests.index')">
                            <div class="flex gap-x-2 items-center">
                                <x-icons.user-plus />
                                {{ __('Friend Requests') }}
                            </div>
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('settings.edit')">
                            <div class="flex gap-x-2 items-center">
                                <x-icons.cog />
                                {{ __('Settings') }}
                            </div>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();"
                            >
                                <div class="flex gap-x-2 items-center">
                                    <x-icons.arrow-turn-down-right />
                                    {{ __('Log Out') }}
                                </div>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        @endauth
        
        </div>
    </div>
</nav>
