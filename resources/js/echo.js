import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Expose Pusher globally (some libs expect it)
window.Pusher = Pusher;

// Create Echo instance and attach to window for global access
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Example: global listener for new jobs – adapt in specific pages if needed
window.Echo.channel('jobs').listen('JobPosted', (e) => {
    console.log('Real-time: new job posted', e);
    // You can dispatch a custom event so individual pages can decide what to do
    window.dispatchEvent(new CustomEvent('job-posted', { detail: e }));
});
