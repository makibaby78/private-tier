<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chat Room
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div id="messages" class="mb-4 max-h-64 overflow-y-auto border p-4 rounded bg-gray-50">
                        {{-- Existing messages will appear here --}}
                    </div>

                    <form id="message-form">
                        @csrf
                        <div class="flex">
                            <input
                                type="text"
                                id="message-input"
                                name="message"
                                placeholder="Type your message..."
                                class="w-full border rounded-l px-4 py-2"
                                required
                            >
                            <button
                                type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700"
                            >
                                Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JS Script --}}
    <script>
        const form = document.getElementById('message-form');
        const input = document.getElementById('message-input');
        const messages = document.getElementById('messages');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const messageText = input.value.trim();
            if (!messageText) return;

            fetch('/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ message: messageText })
            })
            .then(res => {
                if (!res.ok) throw new Error(`Error ${res.status}`);
                input.value = '';
            })
            .catch(err => console.error('Send failed:', err));
        });

        // Optional: Enable Laravel Echo if you're using Pusher
        if (window.Echo) {
            Echo.channel('chat')
                .listen('MessageSent', (e) => {
                    const div = document.createElement('div');
                    div.textContent = e.message.content;
                    messages.appendChild(div);
                });
        }
        
        
    </script>
</x-app-layout>
