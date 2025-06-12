<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chat Room
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto p-4">
        <h2 class="text-lg font-semibold mb-4">Chat with {{ $receiver->name }}</h2>
    
        <div id="messages" class="border p-4 h-64 overflow-y-auto mb-4 bg-white rounded shadow">
            @foreach ($messages as $message)
                <p><strong>{{ $message->sender->id === auth()->id() ? 'You' : $message->sender->name }}:</strong> {{ $message->message }}</p>
            @endforeach
        </div>
    
        <form id="chat-form">
            <input type="hidden" id="receiver_id" value="{{ $receiver->id }}">
            <input type="text" id="message" class="border rounded p-2 w-full mb-2" placeholder="Type a message..." required>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send</button>
        </form>
    </div>
</x-app-layout>
