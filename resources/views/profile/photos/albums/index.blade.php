<x-app-layout>

    @include('profile.partials.profile-header')

    @include('profile.photos.partials.photos-header', [
        'photoTab' => $photoTab ?? 'tagged',
        'user' => $user
    ])
</x-app-layout>