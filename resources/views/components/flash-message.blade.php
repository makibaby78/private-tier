<div
    x-data="{ show: false, message: 'Operation success', type: 'success' }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
    x-init="
        window.addEventListener('flash-message', () => {
            show = true;
            setTimeout(() => show = false, 3000);
        });
    "
    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full px-4"
    style="display: none;"
>
    <div
        class="px-4 py-3 rounded shadow-lg text-sm text-white"
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-blue-600': type === 'info'
        }"
    >
        Operation success
    </div>
</div>
