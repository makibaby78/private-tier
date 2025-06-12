import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

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
        console.log(`Listening on: chat.${userId}`);

        window.Echo.private(`chat.${userId}`)
            .listen('.MessageSent', (e) => {
                console.log('[DEBUG] Received MessageSent event:', e);

                const messages = document.getElementById('messages');
                const msg = document.createElement('p');
                msg.innerHTML = `<strong>${e.sender_name}:</strong> ${e.message}`;
                messages.appendChild(msg);
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
