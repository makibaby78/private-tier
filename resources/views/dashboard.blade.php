<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>
    <div>
        <div class="right"></div>
        <div class="middle max-w-3xl mx-auto">
            <livewire:post-form />
        </div>
        <div class="left"></div>
    </div>
</x-app-layout>
