<div class="bg-white dark:bg-gray-800 shadow-sm h-full fixed w-60 p-4 flex flex-col gap-y-3">
    <h2 class="pl-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Friends') }}
    </h2>

    <a href="{{ route('friends.index') }}">
        <div class="p-2 text-gray-800 dark:text-gray-200 w-full rounded leading-tight hover:bg-gray-100 hover:text-gray-800">
            Home
        </div>
    </a>

    <a href="{{ route('friends.requests.index') }}">
        <div class="p-2 text-gray-800 dark:text-gray-200 w-full rounded leading-tight hover:bg-gray-100 hover:text-gray-800">
            Friend Requests
        </div>
    </a>

    <a href="{{ route('friends.suggestions.index') }}">
        <div class="p-2 text-gray-800 dark:text-gray-200 w-full rounded leading-tight hover:bg-gray-100 hover:text-gray-800">
            Suggestions
        </div>
    </a>

    <a href="{{ route('friends.all-friends.index') }}">
        <div class="p-2 text-gray-800 dark:text-gray-200 w-full rounded leading-tight hover:bg-gray-100 hover:text-gray-800">
            All Friends
        </div>
    </a>

    <a href="{{ route('friends.birthdays.index') }}">
        <div class="p-2 text-gray-800 dark:text-gray-200 w-full rounded leading-tight hover:bg-gray-100 hover:text-gray-800">
            Birthdays
        </div>
    </a>
</div>