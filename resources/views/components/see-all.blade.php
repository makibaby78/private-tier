@props(['requests'])

<div class="space-y-4 p-4">
    <div class="flex justify-between">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 leading-tight">Friend Requests</h2>

        <a href="{{ route('friends.requests.index') }}" class="text-blue-600 hover:underline">
            See all
        </a>
    </div>
    <div class="flex gap-x-2 flex-wrap">
        @forelse ($requests as $user)
            <div class="bg-white shadow rounded overflow-hidden" style="width: fit-content;">
                <a href="{{ route('profile.index', $user->username) }}">
                    <x-profile-photo 
                        :path="$user->profile_public_id" 
                        :alt="$user->name" 
                        class="object-cover w-52 h-52" 
                        width="100" 
                        height="100" 
                    />
                </a>
                <div class="p-3">
                    <div>
                        <a href="{{ route('profile.index', $user->username) }}">
                            <p class="font-semibold mb-3">{{ $user->name }}</p>
                        </a>
                    </div>
                    <div class="space-y-2">
                        <button wire:click="accept({{ $user->id }})" class="w-full block bg-blue-500 text-white px-3 py-1 rounded">
                            Confirm
                        </button>
                        <button wire:click="decline({{ $user->id }})" class="w-full block bg-red-500 text-white px-3 py-1 rounded">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No pending friend requests.</p>
        @endforelse
    </div>
</div>
