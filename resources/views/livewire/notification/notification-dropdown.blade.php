<div class="relative" x-data="{ open: false }">

<button @click="open = !open" class="relative p-2 text-white">
{{-- <button @click="open = !open; if(open) { Livewire.emit('markAllAsRead'); }" class="relative p-2 text-white"> --}}
    <i class="fa-solid fa-bell text-xl"></i>


    @if ($unreadCount > 0)
        <span class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
            {{ $unreadCount }}
        </span>
    @endif
</button>



    <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 top-10 w-80 bg-white shadow-2xl rounded-lg overflow-hidden z-50">

        <div class="px-4 py-2 bg-white border-b flex justify-between items-center">
            <h3 class="text-lg text-primary-600 font-semibold">Notifications</h3>


            @if ($unreadCount > 0)
                <button
                   type="button"
                    wire:click="markAllAsRead"
                    class="text-sm font-medium text-white bg-primary-600 px-3 py-1.5 rounded-md shadow-md transition duration-200
                           hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    ✅ Mark all as read
                </button>
            @endif
        </div>



    <div class="max-h-96 overflow-y-auto">
    @forelse ($notifications as $notification)
        @php
            $isUnread = is_null($notification->read_at);
            $textColor = $isUnread ? 'text-primary-900 font-bold' : 'text-gray-600';
            $messageColor = $isUnread ? 'text-green-600' : 'text-gray-500';
            $url = $notification->data['url'] ?? null;
        @endphp

        @if ($url)
            <div
                x-data
                @click.prevent="
                    $wire.markAsRead('{{ $notification->id }}').then(() => {
                        open = false;
                        window.location.href = '{{ $url }}';
                    })
                "
                class="flex flex-col p-3 border-b hover:bg-primary-50 transition duration-150 ease-in-out cursor-pointer"
            >
        @else
            <div
                wire:click="markAsRead('{{ $notification->id }}')"
                class="flex flex-col p-3 border-b hover:bg-primary-50 transition duration-150 ease-in-out cursor-pointer"
            >
        @endif

            <p class="text-sm {{ $textColor }}">{{ $notification->data['title'] ?? 'New Notification' }}</p>
            <p class="text-xs {{ $messageColor }} line-clamp-2">
                {{ $notification->data['message'] ?? 'You have a new notification.' }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
            </p>

            @if ($isUnread)
                <div class="w-2 h-2 bg-red-500 rounded-full self-end mt-1"></div>
            @endif
        </div>

    @empty
        <div class="text-center p-4 text-primary-600 font-semibold">
            No Notifications Available
        </div>
    @endforelse
</div>



        @if ($notifications->count())
        <a href="{{ route('notification.all') }}"
           class="block w-full p-3 text-center text-primary-600 hover:text-primary-600 hover:bg-primary-100 transition duration-150 ease-in-out">
            View All Notifications
        </a>
    @endif


    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if ({{ auth()->check() ? 'true' : 'false' }}) {
                console.log("📡 Subscribing to: notifications.{{ auth()->id() }}");

                window.Echo.channel(`notifications.{{ auth()->id() }}`)
                    .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (notification) => {
                        console.log("🔔 New Notification:", notification);
                        window.livewire.emit('refreshNotifications');
                    });
            } else {
                console.warn("⚠️ User is not authenticated, skipping WebSocket subscription.");
            }
        });
    </script>

</div>
