<div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
    <h2 class="text-lg font-semibold mb-4 dark:text-white">Intro</h2>

    <ul class="space-y-4 text-sm text-gray-700 dark:text-gray-300">
        {{-- Current Job --}}
        @if ($user->currentJob && $user->currentJob->visibility === 'public')
            <li class="flex items-center gap-x-1">
                <x-icons.suitcase /> <p>Fullstack Web Developer at <strong>{{ $user->currentJob->company }}</strong></p>
            </li>
        @endif

        {{-- Previous Jobs --}}
        @foreach ($user->previousJobs as $job)
            @if ($job->visibility === 'public')
                <li class="flex items-center gap-x-1">
                    <x-icons.suitcase /> <p>Former {{ $job->position }} at <strong>{{ $job->company }}</strong></p>
                </li>
            @endif
        @endforeach

        {{-- Education --}}
        @foreach ($user->educations as $edu)
            @if ($edu->visibility === 'public')
                <li class="flex items-center gap-x-1">
                    <x-icons.school /> <p>Studied {{ $edu->course }} at <strong>{{ $edu->school }}</strong></p>
                </li>
            @endif
        @endforeach

        {{-- Places --}}
        @if ($user->currentCity && $user->currentCity->visibility === 'public')
            <li class="flex items-center gap-x-1">
                <x-icons.house /> <p>Lives in <strong>{{ $user->currentCity->city->name }}</strong></p>
            </li>
        @endif

        @if ($user->hometown && $user->hometown->visibility === 'public')
            <li class="flex items-center gap-x-1">
                <x-icons.pin /> <p>From <strong>{{ $user->hometown->city->name }}</strong></p>
            </li>
        @endif

        @if ($canViewRelationship && $relationship)
            <li>
                <strong>{{ ucwords(str_replace('_', ' ', $relationship->status)) }}</strong>
        
                @if ($relationship->partner && $relationship->confirmed)
                    with <a href="{{ route('profile.index', $relationship->partner->username) }}" class="text-blue-600 hover:underline">
                        {{ $relationship->partner->name }}
                    </a>
                @endif
        
                @if ($relationship->since)
                    since {{ \Carbon\Carbon::parse($relationship->since)->format('F Y') }}
                @endif
        
                @if ($isOwner && !$relationship->confirmed && $relationship->partner_id)
                    <div class="text-xs text-yellow-600 dark:text-yellow-400">
                        Pending confirmation from partner. This won't be visible publicly until confirmed.
                    </div>
                @endif
            </li>
        @endif

        {{-- Website from contacts --}}
        @php
            $website = $user->contacts->firstWhere(fn($contact) => $contact->type === 'website' && $contact->visibility === 'public');
        @endphp
        @if ($website)
            <li class="flex items-center gap-x-1">
                <x-icons.globe />
                <a href="{{ $website->value }}" class="text-blue-600 hover:underline" target="_blank">
                    {{ $website->value }}
                </a>
            </li>
        @endif
    </ul>
</div>
