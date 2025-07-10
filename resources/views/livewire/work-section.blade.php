<div class="space-y-4">
    <h2 class="dark:text-white text-lg font-semibold">Work</h2>

    @if ($isOwner)
        @if ($showForm)
            <form wire:submit="{{ $editingId ? 'update' : 'save' }}" class="space-y-3">
                <x-text-input wire:model.live="position" id="position" name="position" type="text" class="mt-1 block w-full"  autocomplete="position" placeholder="Position" />
                <x-text-input wire:model.live="company" id="company" name="company" type="text" class="mt-1 block w-full" autocomplete="company" placeholder="Company" />
                <x-text-input wire:model.live="location" id="location" name="location" type="text" class="mt-1 block w-full" autocomplete="location" placeholder="Location" />

                <div class="flex gap-2">
                    <div>
                        <x-input-label :value="__('Start Date')" />
                        <x-text-input wire:model.live="start_date" type="date" class="border p-2 rounded" />
                    </div>

                    @unless($is_current)
                        <div>
                            <x-input-label :value="__('End Date')" />
                            <x-text-input wire:model.live="end_date" type="date" class="border p-2 rounded" />
                        </div>
                    @endunless
                </div>

                <label class="flex items-center gap-2 dark:text-white">
                    <input type="checkbox" wire:model.live="is_current" />
                    Currently working here
                </label>

                <div class="max-w-xs">
                    <select wire:model.live="visibility" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="public">Public</option>
                        <option value="friends">Friends</option>
                        <option value="only_me">Only Me</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        {{ $editingId ? 'Update' : 'Save' }}
                    </button>
                    <button type="button" wire:click="cancelEdit" class="text-gray-600">Cancel</button>
                </div>
            </form>
        @else
            <button wire:click="$set('showForm', true)" class="flex items-center gap-2 text-blue-600 hover:underline">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 4v16m8-8H4"/></svg>
                <span>Add a workplace</span>
            </button>
        @endif
    @endif

    @foreach ($this->experiences as $exp)
        <div class="flex items-start justify-between bg-white dark:bg-gray-900 rounded-lg p-4 shadow-sm border dark:border-gray-700">
            <div class="flex gap-3">
                <div class="w-10 h-10">
                    <img src="https://via.placeholder.com/40x40/1f2937/ffffff.png?text=Web" alt="Company Logo" class="rounded-full w-10 h-10 object-cover">
                </div>

                <div>
                    <div class="text-sm text-gray-800 dark:text-white">
                        <span class="font-semibold">{{ $exp->position }}</span>
                        <span> at </span>
                        <span class="font-semibold">{{ $exp->company }}</span>
                    </div>

                    <div class="text-xs text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($exp->start_date)->format('F d, Y') }}
                        –
                        {{ $exp->is_current ? 'Present' : \Carbon\Carbon::parse($exp->end_date)->format('F d, Y') }}
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @if ($exp->visibility === 'public')
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 1010 10A10 10 0 0012 2zm6.93 6H15.6a12.35 12.35 0 00-1.57-3.79A8.015 8.015 0 0118.93 8zM12 4c.8.92 1.56 2.28 2.06 4H9.94C10.44 6.28 11.2 4.92 12 4zM4.24 14a8.05 8.05 0 010-4h3.5a15.9 15.9 0 000 4zm.83 2h3.53a12.35 12.35 0 001.57 3.79A8.015 8.015 0 015.07 16zM8.4 16h7.2a13.56 13.56 0 01-1.7 3.87A13.56 13.56 0 018.4 16zm7.86 1.79A12.35 12.35 0 0015.6 16h3.53a8.015 8.015 0 01-2.87 3.79zM19.76 10a8.05 8.05 0 010 4h-3.5a15.9 15.9 0 000-4zM15.6 10a13.56 13.56 0 000 4H8.4a13.56 13.56 0 000-4zM8.4 8h7.2a13.56 13.56 0 00-1.7-3.87A13.56 13.56 0 008.4 8zM5.07 8A8.015 8.015 0 018.4 4.21 12.35 12.35 0 006.83 8z"/>
                    </svg>
                @endif

                @if ($isOwner)
                    <button wire:click="edit({{ $exp->id }})" class="hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full p-1 transition">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 20.5H3v-3.5L16.732 3.732z"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    @endforeach
</div>
