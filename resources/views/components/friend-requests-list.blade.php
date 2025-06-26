@props(['requests'])

<div class="bg-white dark:bg-gray-800 shadow-sm h-full fixed max-w-xs p-4 flex flex-col gap-y-3">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Friends') }}
    </h2>
    <p class="text-gray-800 dark:text-gray-200 leading-tight">{{ $requests->count() }} Friend Requests</p>

    <div class="flex flex-col gap-y-6">
        @forelse ($requests as $user)
            <div class="flex gap-x-2">
                <x-profile-photo 
                    :path="$user->profile_public_id" 
                    :alt="$user->name" 
                    class="object-cover rounded-full w-12 h-12" 
                    width="50" 
                    height="50" 
                />
                <div>
                    <h3 class="text-gray-800 dark:text-gray-200 leading-tight mb-2">{{ $user->name }}</h3>
                    <div class="flex gap-x-2">
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