<div class="space-y-4">
    @if ($isOwner || $visiblePlaces->count())
        <h2 class="text-lg font-semibold dark:text-white">Places</h2>
    @endif

    @if ($isOwner && !$showForm)
        <button wire:click="$set('showForm', true)" class="text-blue-600 hover:underline flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add a place</span>
        </button>
    @endif

    @if ($showForm)
        <form wire:submit="{{ $editingId ? 'update' : 'save' }}" class="space-y-3 bg-white dark:bg-gray-800 p-4 rounded-md border dark:border-gray-700">
            <select id="address_type" wire:model.live="type" class="w-full border dark:bg-gray-900 dark:text-white rounded">
                <option value="current_city">Current City</option>
                <option value="hometown">Hometown</option>
            </select>

            <select wire:model.change="countryId" class="w-full border dark:bg-gray-900 dark:text-white rounded">
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>

            <select wire:model="city_id" class="w-full border dark:bg-gray-900 dark:text-white rounded">
                <option value="">Select City</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
            
            <x-text-input id="address_region" wire:model.live="region" placeholder="Region" class="w-full" />

            <select id="address_visibility" wire:model.live="visibility" class="w-full border dark:bg-gray-900 dark:text-white rounded">
                <option value="public">Public</option>
                <option value="friends">Friends</option>
                <option value="only_me">Only Me</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    {{ $editingId ? 'Update' : 'Save' }}
                </button>
                <button type="button" wire:click="resetForm" class="text-gray-500 dark:text-gray-300">Cancel</button>
            </div>
        </form>
    @endif

    @foreach ($visiblePlaces as $place)
        <div class="flex justify-between items-start bg-white dark:bg-gray-900 border dark:border-gray-700 p-4 rounded-md shadow-sm">
            <div>
                <div class="text-sm text-gray-800 dark:text-white">
                    <span class="font-semibold">
                        {{ $place->type === 'current_city' ? 'Lives in' : 'From' }}
                    </span>
                    <span> {{ $place->city->name }}</span>
                </div>

                @if ($place->region || $place->country)
                    <div class="text-xs text-gray-500">
                        {{ $place->region }}{{ $place->region && $place->country->name ? ', ' : '' }}{{ $place->country->name }}
                    </div>
                @endif
            </div>

            @if ($isOwner)
            <div class="flex gap-x-2 items-center">
                <button wire:click="edit({{ $place->id }})" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 20.5H3v-3.5L16.732 3.732z"/>
                    </svg>
                </button>

                <button
                    x-data
                    x-on:click.prevent="
                        $wire.confirmDelete({{ $place->id }});
                        $dispatch('open-modal', 'delete-place');
                    "
                    class="text-red-500 hover:underline"
                >
                    {{ __('Delete') }}
                </button>
            </div>
            @endif
        </div>
    @endforeach

    <x-modal name="delete-place" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Delete Place
            </h2>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Are you sure you want to delete this place? This action cannot be undone.
            </p>

            <div class="mt-6 flex justify-end gap-3">

                <button
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2 text-sm rounded-md border border-gray-300
                        text-gray-700 hover:bg-gray-100
                        dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    Cancel
                </button>

                <button
                    x-on:click="$dispatch('close')"
                    wire:click="deletePlace()"
                    class="px-4 py-2 text-sm rounded-md
                        bg-red-600 text-white hover:bg-red-700
                        focus:outline-none focus:ring-2 focus:ring-red-500"
                >
                    Delete
                </button>

            </div>
        </div>
    </x-modal>
</div>

