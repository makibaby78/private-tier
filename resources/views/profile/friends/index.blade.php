<x-app-layout>

    @include('profile.partials.profile-header')
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4">
        <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <livewire:friends :user="$user" />
        </div>
    </div>
</x-app-layout>