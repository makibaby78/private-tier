<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('settings.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="firstname" :value="__('First Name')" />
            <x-text-input id="firstname" name="firstname" type="text" class="mt-1 block w-full" :value="old('firstname', $user->firstname)" required autofocus autocomplete="firstname" />
            <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
        </div>

        <div>
            <x-input-label for="middlename" :value="__('Middle Name (optional)')" />
            <x-text-input id="middlename" name="middlename" type="text" class="mt-1 block w-full" :value="old('middlename', $user->middlename)" autofocus autocomplete="middlename" />
            <x-input-error class="mt-2" :messages="$errors->get('middlename')" />
        </div>

        <div>
            <x-input-label for="lastname" :value="__('Last Name')" />
            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname', $user->lastname)" required autofocus autocomplete="lastname" />
            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
        </div>

        <div>
            <x-input-label for="nickname" :value="__('Nickname (optional)')" />
            <x-text-input id="nickname" name="nickname" type="text" class="mt-1 block w-full" :value="old('nickname', $user->nickname)" autofocus autocomplete="nickname" />
            <x-input-error class="mt-2" :messages="$errors->get('nickname')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @php
            $months = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
        
            $selected = [
                'day' => old('birth_day', optional($user->birthdate ? \Carbon\Carbon::parse($user->birthdate) : null)?->day),
                'month' => old('birth_month', optional($user->birthdate ? \Carbon\Carbon::parse($user->birthdate) : null)?->month),
                'year' => old('birth_year', optional($user->birthdate ? \Carbon\Carbon::parse($user->birthdate) : null)?->year),
            ];
        @endphp
        
        <div class="mb-4" x-data="{
            month: '{{ $selected['month'] }}',
            year: '{{ $selected['year'] }}',
            get daysInMonth() {
                let m = parseInt(this.month);
                let y = parseInt(this.year) || new Date().getFullYear();
                return m ? new Date(y, m, 0).getDate() : 31;
            }
        }">
            <x-input-label for="birthdate" :value="__('Birthdate')" />
        
            <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2">
                {{-- Month --}}
                <select name="birth_month" id="birth_month" x-model="month"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Month</option>
                    @foreach ($months as $num => $name)
                        <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
        
                {{-- Day --}}
                <select name="birth_day" id="birth_day"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Day</option>
                    <template x-for="d in daysInMonth" :key="d">
                        <option :value="d" x-text="d" {{ old('birth_day') == 'd' ? 'selected' : '' }}></option>
                    </template>
                </select>
        
                {{-- Year --}}
                <select name="birth_year" id="birth_year" x-model="year"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Year</option>
                    @for ($i = now()->year; $i >= 1900; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        
            <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
        </div>
    
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>
