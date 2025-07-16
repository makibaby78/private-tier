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
                    <x-icons.bell />
                </button>
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <div x-data="{ unreadCount: 0 }" @update-unread-user-count.window="unreadCount = $event.detail.count" class="relative">
                            <button
                                @click="window.Livewire.dispatch('refresh-chat-list')"
                                class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center relative"
                            >
                                <x-icons.messenger />
                        
                                <!-- 🔴 Badge -->
                                <template x-if="unreadCount > 0">
                                    <span
                                        x-text="unreadCount"
                                        class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-semibold w-5 h-5 flex items-center justify-center rounded-full"
                                    ></span>
                                </template>
                            </button>
                        </div>
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
                                <x-icons.dropdown />
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
