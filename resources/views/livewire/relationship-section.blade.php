<div class="space-y-4">
    @if ($isOwner || $canView)
        <h2 class="text-lg font-semibold dark:text-white">Relationship</h2>
    @endif

    @if ($isOwner && !$editing)
        <button wire:click="edit" class="text-blue-600 hover:underline">
            Edit Relationship
        </button>
    @endif

    @if ($editing)
        <form wire:submit.prevent="save" class="space-y-3 bg-white dark:bg-gray-800 p-4 rounded-md border dark:border-gray-700">
            <select wire:model.live="status" class="w-full border rounded dark:bg-gray-900 dark:text-white">
                @foreach (['single', 'in_a_relationship', 'engaged', 'married', 'complicated', 'separated', 'divorced', 'widowed'] as $option)
                    <option value="{{ $option }}">{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                @endforeach
            </select>

            @if ($status !== 'single')
                <input type="number" wire:model.live="partner_id" class="w-full border rounded dark:bg-gray-900 dark:text-white" placeholder="Partner User ID (optional)">
                <input type="date" wire:model.live="since" class="w-full border rounded dark:bg-gray-900 dark:text-white">
            @endif

            <select wire:model.live="visibility" class="w-full border rounded dark:bg-gray-900 dark:text-white">
                <option value="public">Public</option>
                <option value="friends">Friends</option>
                <option value="only_me">Only Me</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                <button type="button" wire:click="cancel" class="text-gray-600 dark:text-gray-300">Cancel</button>
            </div>
        </form>
    @endif

    @if ($canView && $relationship)
        <div class="text-sm text-gray-800 dark:text-white">
            {{ ucwords(str_replace('_', ' ', $relationship->status)) }}
            @if ($relationship->partner_id && $partnerName)
                with <span class="font-semibold">{{ $partnerName }}</span>
            @endif
            @if ($relationship->since)
                since {{ \Carbon\Carbon::parse($relationship->since)->format('F Y') }}
            @endif
        </div>
    @endif
</div>
