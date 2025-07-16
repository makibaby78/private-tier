<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <a href="{{ route('google.redirect') }}" 
               class="inline-flex items-center justify-center w-full px-4 py-2 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 533.5 544.3" xmlns="http://www.w3.org/2000/svg">
                    <path d="M533.5 278.4c0-17.4-1.6-34.1-4.6-50.4H272v95.3h146.9c-6.3 34.2-25.1 63.2-53.8 82.6v68.3h86.8c50.8-46.9 81.6-115.9 81.6-195.8z" fill="#4285F4"/>
                    <path d="M272 544.3c72.6 0 133.5-24 178-65.4l-86.8-68.3c-24.1 16.2-55.1 25.9-91.2 25.9-70.1 0-129.4-47.3-150.6-110.8H34.1v69.7c44.4 87.8 136.1 148.9 237.9 148.9z" fill="#34A853"/>
                    <path d="M121.4 325.7c-10.1-29.9-10.1-62.1 0-91.9v-69.7H34.1c-36.1 70.1-36.1 153.4 0 223.5l87.3-62z" fill="#FBBC05"/>
                    <path d="M272 107.7c39.5 0 75.1 13.6 103.2 40.3l77.4-77.4C405.5 24.3 344.6 0 272 0 170.2 0 78.5 61.1 34.1 148.9l87.3 62c21.2-63.5 80.5-110.8 150.6-110.8z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </a>
        </div>        
        
        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
