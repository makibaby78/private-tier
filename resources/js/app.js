import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

// Send Message
document.addEventListener('DOMContentLoaded', () => {

    const userId = document.querySelector('meta[name="user-id"]')?.content;

    if (userId) {

        window.Echo.private(`chat.${userId}`)
            .listen('.MessageSent', (e) => {
                // ✅ Call Livewire to update the UI reactively
                Livewire.dispatch('message-received', {
                    id: e.id,
                    message: e.message,
                    type: e.type,
                    sender_id: e.sender_id,
                    sender_name: e.sender_name,
                });

                // Optional scroll
                window.dispatchEvent(new CustomEvent('scroll-chat', {
                    detail: { userId: e.sender_id }
                }));
            });

    }

    const form = document.getElementById('chat-form');
    const receiverId = document.getElementById('receiver_id')?.value;
    const messageInput = document.getElementById('message');

    if (form && receiverId && messageInput) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const message = messageInput.value;
            if (!message) return;

            await axios.post('/chat/send', {
                receiver_id: receiverId,
                message,
            });

            const messages = document.getElementById('messages');
            const msg = document.createElement('p');
            msg.innerHTML = `<strong>You:</strong> ${message}`;
            messages.appendChild(msg);
            messageInput.value = '';
        });
    }
});
