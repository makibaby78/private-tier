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
    forceTLS: true
});

// Listen to the private channel and event
window.Echo.private('chat.1')
    .listen('.MessageSent', (e) => {
        console.log('📥 Message received from Laravel:', e.message);
    });
