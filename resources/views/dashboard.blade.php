<x-app-layout>
    <div>
        <div class="left relative hidden lg:block">
            <div class="h-full fixed w-60 p-4 flex flex-col gap-y-3">
                <a href="{{ route('profile.index', ['username' => Auth::user()->username]) }}">
                    <div class="p-2 text-gray-800 dark:text-gray-200 w-full rounded leading-tight hover:bg-gray-100 hover:text-gray-800 text-sm flex gap-x-1 items-center">
                        <x-profile-photo 
                            :path="Auth::user()->profile_public_id" 
                            :alt="Auth::user()->name" 
                            class="rounded-full object-cover w-8 h-8 max-w-none" 
                            width="50" 
                            height="50" 
                        />
                        {{ Auth::user()->name }}
                    </div>
                </a>

                <a href="{{ route('friends.index') }}">
                    <div class="p-2 text-gray-800 dark:text-gray-200 w-full rounded leading-tight hover:bg-gray-100 hover:text-gray-800 text-sm">
                        Friends
                    </div>
                </a>
            </div>
        </div>
        <div class="middle lg:max-w-2xl max-w-lg mx-auto py-4 space-y-4">
            <livewire:post-form />
            <livewire:feed />
        </div>
        <div class="right"></div>
    </div>
</x-app-layout>
