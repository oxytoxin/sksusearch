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




// // ✅ Ensure user is authenticated before subscribing
// if (window.Laravel?.userId) {
//     console.log(`🔗 Subscribing to private channel: notifications.${window.Laravel.userId}`);

//     window.Echo.private(`notifications.${window.Laravel.userId}`)
//         .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (notification) => {
//             console.log("🔔 New Notification:", notification);

//             // ✅ Emit Livewire event to refresh notification dropdown
//             if (typeof Livewire !== "undefined") {
//                 Livewire.emit('refreshNotifications');
//             } else {
//                 console.warn("⚠️ Livewire is not available yet!");
//             }
//         });
// } else {
//     console.warn("⚠️ User is not authenticated, skipping notification subscription.");
// }
