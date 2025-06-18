<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
        <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex items-center justify-between flex-col md:flex-row gap-y-4 gap-x-2">

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
                
                <button 
                    x-data 
                    @click="window.chatManager.call('openChat', 3)"
                    class="px-4 py-2 bg-blue-600 text-white rounded"
                >
                    Toggle Chat with User 3
                </button>



        </div>
    </div>

    <div class="profile-l-r max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-4">
        <div class="profile-left w-full lg:w-2/5">

            <livewire:my-friends-list :user="$user" />

        </div>

        <div class="profile-right w-full lg:w-3/5">
            @auth
                <livewire:post-form />
            @endauth
            
            <livewire:post-list :user="$user" />

        </div>
    </div>
</x-app-layout>