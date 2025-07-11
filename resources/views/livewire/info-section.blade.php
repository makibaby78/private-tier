<div class="space-y-4">
    @if ($isOwner || $contacts->isNotEmpty())
        <h2 class="text-lg font-semibold dark:text-white">Contact Info</h2>
    @endif

    @if ($isOwner && !$showForm)
        <button wire:click="$set('showForm', true)" class="flex items-center gap-2 text-blue-600 hover:underline">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add contact</span>
        </button>
    @endif

    @if ($showForm)
        <form wire:submit="save" class="space-y-3 bg-white dark:bg-gray-800 p-4 rounded-md border dark:border-gray-700">
            <select id="type" name="type" wire:model.live="type" class="w-full border rounded dark:bg-gray-900 dark:text-white">
                <option value="phone">Phone</option>
                <option value="email">Email</option>
                <option value="website">Website</option>
                <option value="other">Other</option>
            </select>

            {{-- Conditional input types --}}
            @if ($type === 'email')
                <input type="email" id="value" name="value" wire:model.live="value" placeholder="Enter email address" class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white">
            @elseif ($type === 'phone')
                <input type="tel" id="value" name="value" wire:model.live="value" placeholder="Enter phone number" class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white">
            @elseif ($type === 'website')
                <input type="url" id="value" name="value" wire:model.live="value" placeholder="https://example.com" class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white">
            @else
                <input type="text" id="value" name="value" wire:model.live="value" placeholder="Contact value" class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white">
            @endif

            <input type="text" id="label" name="label" wire:model.live="label" placeholder="Label (e.g. Mobile, Work)" class="w-full px-3 py-2 border rounded dark:bg-gray-900 dark:text-white">

            <select id="visibility" name="visibility" wire:model.live="visibility" class="w-full border rounded dark:bg-gray-900 dark:text-white">
                <option value="public">Public</option>
                <option value="friends">Friends</option>
                <option value="only_me">Only Me</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                <button type="button" wire:click="resetForm" class="text-gray-600 dark:text-gray-300">Cancel</button>
            </div>
        </form>
    @endif


    @foreach ($contacts as $contact)
        <div class="flex items-start justify-between bg-white dark:bg-gray-900 rounded-lg p-4 shadow-sm border dark:border-gray-700">
            <div>
                <div class="text-sm text-gray-800 dark:text-white font-semibold">
                    {{ ucfirst($contact->type) }}:
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $contact->value }}
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @if ($contact->visibility === 'public')
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 1010 10A10 10 0 0012 2z"/>
                    </svg>
                @endif

                @if ($isOwner)
                    <button wire:click="edit({{ $contact->id }})" class="hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full p-1 transition">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M6.5 20.5H3v-3.5L16.732 3.732z"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    @endforeach
</div>
