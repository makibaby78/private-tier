<x-app-layout>
    <div class="relative flex gap-x-2">
        <livewire:friend-request-list :view="'components.friend-requests-list'" />
        <div class="profile" style="width: calc(100% - 320px); min-width: 500px;">
            @include('profile.partials.profile-header')

            <livewire:profile :user="$user" />
        </div>
    </div>
</x-app-layout>