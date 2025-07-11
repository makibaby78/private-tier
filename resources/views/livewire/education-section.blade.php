<div class="space-y-4">
    <h2 class="text-lg font-semibold dark:text-white">Education</h2>

    @if ($isOwner && !$showForm)
        <button wire:click="$set('showForm', true)" class="flex items-center gap-2 text-blue-600 hover:underline">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                d="M12 4v16m8-8H4"/></svg>
            <span>Add a school</span>
        </button>
    @endif

    @if ($showForm)
        <form wire:submit="{{ $editingId ? 'update' : 'save' }}" class="space-y-3 bg-white dark:bg-gray-800 p-4 rounded-md border dark:border-gray-700">
            <select id="education_level" wire:model.live="level" class="w-full border rounded dark:bg-gray-900 dark:text-white">
                <option value="college">College</option>
                <option value="highschool">High School</option>
                <option value="others">Others</option>
            </select>

            <x-text-input id="education_school" wire:model.live="school" placeholder="School" class="w-full" />

            @if ($level === 'college')
                <x-text-input id="education_degree" wire:model.live="degree" placeholder="Degree (e.g. BS Computer Science)" class="w-full" />
            @endif

            <div class="flex gap-2">
                <x-text-input id="education_start" wire:model.live="start_date" type="date" class="w-full" />
                @unless($is_current)
                    <x-text-input id="education_end" wire:model.live="end_date" type="date" class="w-full" />
                @endunless
            </div>

            <label class="flex items-center space-x-2">
                <input id="education_current" type="checkbox" wire:model.live="is_current" />
                <span class="text-sm dark:text-white">Currently studying here</span>
            </label>

            <select id="education_visibility" wire:model.live="visibility" class="w-full border rounded dark:bg-gray-900 dark:text-white">
                <option value="public">Public</option>
                <option value="friends">Friends</option>
                <option value="only_me">Only Me</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">{{ $editingId ? 'Update' : 'Save' }}</button>
                <button type="button" wire:click="resetForm" class="text-gray-600 dark:text-gray-300">Cancel</button>
            </div>
        </form>
    @endif

    @if ($canView)
        @foreach ($visibleEducations as $edu)
            <div class="flex items-start justify-between bg-white dark:bg-gray-900 rounded-lg p-4 shadow-sm border dark:border-gray-700">
                <div>
                    <div class="text-sm text-gray-800 dark:text-white">
                        <span class="font-semibold">{{ ucfirst($edu->level) }}</span> at
                        <span class="font-semibold">{{ $edu->school }}</span>
                    </div>

                    @if ($edu->level === 'college' && $edu->degree)
                        <div class="text-sm text-gray-500">{{ $edu->degree }}</div>
                    @endif

                    @if ($edu->start_date || $edu->end_date)
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $edu->start_date ? \Carbon\Carbon::parse($edu->start_date)->format('F Y') : '' }}
                            –
                            {{ $edu->is_current ? 'Present' : ($edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('F Y') : '') }}
                        </div>
                    @endif
                </div>

                <div class="flex items-center space-x-2">
                    @if ($edu->visibility === 'public')
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2a10 10 0 1010 10A10 10 0 0012 2z"/>
                        </svg>
                    @endif

                    @if ($isOwner)
                        <button wire:click="edit({{ $edu->id }})" class="hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full p-1 transition">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 20.5H3v-3.5L16.732 3.732z"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">No education history to show.</p>
    @endif
</div>
