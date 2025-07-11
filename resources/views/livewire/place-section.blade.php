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

            <x-text-input id="address_city" wire:model.live="city" placeholder="City" class="w-full" />
            <x-text-input id="address_region" wire:model.live="region" placeholder="Region" class="w-full" />
            <x-text-input id="address_country" wire:model.live="country" placeholder="Country" class="w-full" />

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
                    <span> {{ $place->city }}</span>
                </div>

                @if ($place->region || $place->country)
                    <div class="text-xs text-gray-500">
                        {{ $place->region }}{{ $place->region && $place->country ? ', ' : '' }}{{ $place->country }}
                    </div>
                @endif
            </div>

            @if ($isOwner)
                <button wire:click="edit({{ $place->id }})" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 20.5H3v-3.5L16.732 3.732z"/>
                    </svg>
                </button>
            @endif
        </div>
    @endforeach
</div>

