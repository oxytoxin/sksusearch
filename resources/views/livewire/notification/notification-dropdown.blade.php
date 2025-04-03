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
        $iconColor = $isUnread ? 'bg-primary-600 text-white' : 'bg-gray-400 text-gray-200';
        $url = $notification->data['url'] ?? null;
    @endphp

    <!-- Wrap with <a> tag if URL exists -->
    @if ($url)
        <a href="{{ $url }}" class="block" wire:click="markAsRead('{{ $notification->id }}')">
    @else
        <div wire:click="markAsRead('{{ $notification->id }}')">
    @endif

        <div class="flex items-start p-3 border-b hover:bg-primary-50 transition duration-150 ease-in-out cursor-pointer">
            <!-- User Initials Instead of Icon -->
            <div class="flex items-center justify-center w-10 h-10 text-sm font-bold {{ $iconColor }} rounded-full shadow">
                {{ strtoupper(substr($notification->data['user'] ?? 'UA', 0, 1)) }}{{ strtoupper(substr($notification->data['user'] ?? 'UA', 1, 1)) }}
            </div>

            <!-- Notification Content -->
            <div class="ml-4 flex-1">
                <p class="text-sm {{ $textColor }}">
                    {{ $notification->data['title'] ?? 'New Notification' }}
                </p>

                <p class="text-xs {{ $messageColor }} line-clamp-2">
                    {{ $notification->data['message'] ?? 'You have a new notification.' }}
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    {{-- {{ \Carbon\Carbon::parse($notification->created_at)->format('l, F j, Y - g:i A') }} --}}
                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                </p>
            </div>

            <!-- Read Status Indicator -->
            @if ($isUnread)
                <div class="w-2 h-2 bg-red-500 rounded-full self-center ml-2"></div>
            @endif
        </div>

    @if ($url)
        </a>
    @else
        </div>
    @endif
@empty
<div class="text-center p-4 ">
  <!-- <svg class="mx-auto size-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22c1.1 0 2-.9 2-2H10c0 1.1.9 2 2 2zm6-6V9c0-3.07-1.63-5.64-4.5-6.32V2a1.5 1.5 0 00-3 0v.68C7.63 3.36 6 5.92 6 9v7l-1.29 1.29a1 1 0 00-.21 1.09c.14.31.45.52.79.52h14c.34 0 .65-.21.79-.52a1 1 0 00-.21-1.09L18 16z" />
  </svg> -->
  <h3 class="mt-2 text-sm font-semibold text-gray-900 text-primary-600">No Notifications Available</h3>
  {{-- <p class="mt-1 text-sm text-gray-500 text-primary-600">You're all caught up! No new notifications.</p> --}}
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
