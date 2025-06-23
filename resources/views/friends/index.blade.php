<x-app-layout>
    <div class="relative flex gap-x-2">
        @include('friends.partials.sidebar')
        <div class="ml-60 w-full">
            <livewire:friend-request-list />
        </div>
    </div>
</x-app-layout> 