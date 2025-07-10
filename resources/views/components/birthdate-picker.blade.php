@props(['day' => null, 'month' => null, 'year' => null, 'errorKey' => 'birthdate'])

@php
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];

    $selected = [
        'day' => old('birth_day', $day),
        'month' => old('birth_month', $month),
        'year' => old('birth_year', $year),
    ];
@endphp

<div class="mt-4" x-data="{
    month: '{{ $selected['month'] }}',
    year: '{{ $selected['year'] }}',
    day: '{{ $selected['day'] }}',
    get daysInMonth() {
        let m = parseInt(this.month);
        let y = parseInt(this.year) || new Date().getFullYear();
        return m ? new Date(y, m, 0).getDate() : 31;
    }
}">
    <x-input-label for="birthdate" :value="__('Birthdate')" />

    <div class="mt-2 grid grid-cols-3 gap-2">
        {{-- Month --}}
        <select name="birth_month" id="birth_month" x-model="month"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Month</option>
            @foreach ($months as $num => $name)
                <option value="{{ $num }}" @selected($selected['month'] == $num)>{{ $name }}</option>
            @endforeach
        </select>

        {{-- Day --}}
        <select name="birth_day" id="birth_day" x-model="day"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Day</option>
            <template x-for="d in daysInMonth" :key="d">
                <option :value="d" x-text="d" :selected="day == d"></option>
            </template>
        </select>

        {{-- Year --}}
        <select name="birth_year" id="birth_year" x-model="year"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Year</option>
            @for ($i = now()->year; $i >= 1900; $i--)
                <option value="{{ $i }}" @selected($selected['year'] == $i)>{{ $i }}</option>
            @endfor
        </select>
    </div>

    <x-input-error class="mt-2" :messages="$errors->get($errorKey)" />
</div>
