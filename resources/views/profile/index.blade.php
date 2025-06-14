<x-profile-layout>
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
                
            @auth
                @if($isOwnProfile)
                    <p>This is your profile.</p>
                    <a href="{{ route('settings.edit') }}">Edit Profile</a>
                @else
                    <p>This is {{ $user->firstname }}'s public profile.</p>

                    @php
                        $authUser = auth()->user();
                    @endphp
                    
                    @if ($authUser->isFriendsWith($user))
                        <p>You are friends</p>
                    
                    @elseif ($authUser->hasSentFriendRequestTo($user))
                        <p>Friend request sent</p>
                        <form action="{{ route('friend.cancel', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit">Cancel Request</button>
                        </form>
                    
                    @elseif ($authUser->hasReceivedFriendRequestFrom($user))
                        <form action="{{ route('friend.accept', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit">Accept Friend Request</button>
                        </form>
                    
                    @elseif ($authUser->id !== $user->id)
                        <!-- You cannot add yourself -->
                        <form action="{{ route('friend.send', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit">Add Friend</button>
                        </form>
                    @endif
                
                @endif
            @endauth
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

</x-profile-layout>