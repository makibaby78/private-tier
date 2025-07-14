<div class="space-y-4">
    {{-- Header --}}
    @if ($isOwner || $canView)
        <h2 class="text-lg font-semibold dark:text-white">Relationship</h2>
    @endif

    {{-- Edit button --}}
    @if ($isOwner && !$editing)
        <button wire:click="edit" class="text-blue-600 hover:underline">
            Edit Relationship
        </button>
    @endif

    {{-- Edit form --}}
    @if ($editing)
        <form wire:submit.prevent="save" class="space-y-3 bg-white dark:bg-gray-800 p-4 rounded-md border dark:border-gray-700">
            {{-- Status --}}
            <select wire:model.live="status" class="w-full border rounded dark:bg-gray-900 dark:text-white">
                @foreach (['single', 'in_a_relationship', 'engaged', 'married', 'complicated', 'separated', 'divorced', 'widowed'] as $option)
                    <option value="{{ $option }}">{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                @endforeach
            </select>

            {{-- Partner & Since --}}
            @if ($status !== 'single')
                <input
                    type="number"
                    wire:model.live="partner_id"
                    class="w-full border rounded dark:bg-gray-900 dark:text-white"
                    placeholder="Partner User ID (optional)"
                >

                <input
                    type="date"
                    wire:model.live="since"
                    class="w-full border rounded dark:bg-gray-900 dark:text-white"
                >

                @if ($partner_id)
                    <div class="text-sm text-yellow-600 dark:text-yellow-400">
                        The relationship will be visible only after your partner confirms it.
                    </div>
                @endif
            @endif

            {{-- Visibility --}}
            <select wire:model.live="visibility" class="w-full border rounded dark:bg-gray-900 dark:text-white">
                <option value="public">Public</option>
                <option value="friends">Friends</option>
                <option value="only_me">Only Me</option>
            </select>

            {{-- Form actions --}}
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                <button type="button" wire:click="cancel" class="text-gray-600 dark:text-gray-300">Cancel</button>
            </div>
        </form>
    @endif

    {{-- Display relationship --}}
    @if ($canView && $relationship)
        <div class="text-sm text-gray-800 dark:text-white">
            {{ ucwords(str_replace('_', ' ', $relationship->status)) }}

            @if ($relationship->partner_id && $partnerName)
                with <span class="font-semibold">{{ $partnerName }}</span>
            @endif

            @if ($relationship->since)
                since {{ \Carbon\Carbon::parse($relationship->since)->format('F Y') }}
            @endif

            @if ($isOwner && !$relationship->confirmed && $relationship->partner_id)
                <div class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                    This relationship is pending confirmation from your partner and is not visible to others.
                </div>
            @endif
        </div>
    @endif

    {{-- Incoming request --}}
    @if ($isOwner && $incomingRequest)
        <div class="bg-yellow-50 dark:bg-gray-700 p-4 rounded border dark:border-gray-600 text-sm">
            <div class="mb-2 text-gray-800 dark:text-gray-100">
                <span class="font-semibold">{{ $incomingRequest->user->name }}</span>
                sent you a relationship request ({{ ucwords(str_replace('_', ' ', $incomingRequest->status)) }}).
            </div>

            <div class="flex gap-2">
                <button wire:click="accept" class="bg-green-600 text-white px-3 py-1 rounded">Accept</button>
                <button wire:click="decline" class="bg-red-600 text-white px-3 py-1 rounded">Decline</button>
            </div>
        </div>
    @endif
</div>
