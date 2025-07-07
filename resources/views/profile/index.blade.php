<x-app-layout>

    @include('profile.partials.profile-header')

    <div class="profile-l-r max-w-7xl mx-auto sm:px-6 md:px-8 flex flex-col md:flex-row gap-4">
        <div class="profile-left w-full md:w-2/5 space-y-4">

            @include('profile.partials.profile-photos')

            <livewire:my-friends-list :user="$user" />

        </div>

        <div class="profile-right space-y-4 w-full md:w-3/5">
            @if (auth()->id() === $user->id)
                <livewire:post-form :from="'timeline'" />
            @endif
            
            <livewire:post-list :user="$user" />

        </div>
    </div>
</x-app-layout>