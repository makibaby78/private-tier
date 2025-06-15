<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex items-center justify-between">

                    <div class="flex items-center gap-x-4">
                        
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
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <livewire:my-friends-list :user="$user" />
            </div>
        </div>
    </div>
</x-app-layout>