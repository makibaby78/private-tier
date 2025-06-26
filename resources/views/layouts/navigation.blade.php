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
                <x-dropdown align="right" width="32">
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
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.index', ['username' => Auth::user()->username])">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('settings.edit')">
                            {{ __('Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        @endauth
        
        </div>
    </div>
</nav>
