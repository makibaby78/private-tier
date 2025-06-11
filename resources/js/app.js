import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

console.log('✅ app.js is loaded');


// Wait until the DOM and Echo are ready
document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.Echo !== 'undefined') {
        window.Echo.channel('chat')
            .listen('MessageSent', (e) => {
                console.log('📩 Message received:', e.message);
                // Optionally append to chat box
            });
    } else {
        console.error('❌ Echo is not defined');
    }
});