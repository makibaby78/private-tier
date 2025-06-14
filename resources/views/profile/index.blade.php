<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Post Listing") }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                    <div class="flex gap-x-4 items-baseline">
                        <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" width="100">
                        <h1 class="mb-4 text-xl font-extrabold leading-none tracking-tight text-gray-900 md:text-3xl lg:text-4xl dark:text-white">{{ $user->name }}</h1>
                    </div>

                    <livewire:friendship-button :targetUser="$user" />
                </div>

                <!-- Send friend request -->
                {{-- <form action="{{ route('friend.send', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit">Add Friend</button>
                </form> --}}

                {{-- <!-- Accept request -->
                <form action="{{ route('friend.accept', $senderId) }}" method="POST">
                    @csrf
                    <button type="submit">Accept Request</button>
                </form>

                <!-- Cancel pending request -->
                <form action="{{ route('friend.cancel', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit">Cancel Request</button>
                </form>

                <!-- Unfriend -->
                <form action="{{ route('friend.remove', $user->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit">Unfriend</button>
                </form> --}}
            </div>
        </div>
    </div>

</x-app-layout>