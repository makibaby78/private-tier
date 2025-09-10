<x-app-layout>

    @include('profile.partials.profile-header')

    <livewire:profile :user="$user" />
</x-app-layout>