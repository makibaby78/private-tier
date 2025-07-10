@if (session('status') || session('message'))
    <div
        x-data="{ show: false }"
        x-init="
            setTimeout(() => show = true, 300);
            setTimeout(() => show = false, 3500);
        "
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 -translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
        class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full px-6"
    >
        <div class="px-4 py-3 rounded-lg shadow-lg border text-sm font-medium
            @if(session('type') === 'error')
                bg-red-700 text-white border-red-800
            @elseif(session('type') === 'info')
                bg-blue-700 text-white border-blue-800
            @else
                bg-green-700 text-white border-green-800
            @endif"
        >
            <div class="flex items-center gap-2">
                <span>
                    @if(session('type') === 'error')
                        ❌
                    @elseif(session('type') === 'info')
                        ℹ️
                    @else
                        ✅
                    @endif
                </span>
                <span>
                    {{ session('message', 'Operation completed.') }}
                </span>
            </div>
        </div>
    </div>
@endif
