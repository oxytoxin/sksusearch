import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || "pusherkey", // Fallback key
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || "mt1", // ✅ Fix missing cluster
    wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname, // ✅ Fix WebSocket Host
    wsPort: import.meta.env.VITE_PUSHER_PORT || 6001,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

console.log("✅ WebSocket Setup Completed");

// Test WebSocket Connection
window.Echo.channel("testchannel")
    .listen(".TestEvent", (e) => {
        console.log("📢 WebSocket Event Received:", e);
        if (typeof Livewire !== "undefined") {
            Livewire.emit('incrementCounter'); // ✅ Correct for Livewire 2
        } else {
            console.warn("⚠️ Livewire is not available yet!");
        }
    });
