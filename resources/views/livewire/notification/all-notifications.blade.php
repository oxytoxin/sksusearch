

<div class="max-w-4xl mx-auto py-8 px-4 bg-white rounded-sm">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">All Notifications</h1>
        <div class="flex justify-end space-x-2">

            <x-filament-support::button icon="heroicon-s-arrow-left" type="button" color="secondary" onclick="window.history.back()" >   Back</x-filament-support::button>
            @if (auth()->user()->unreadNotifications->count())
            <button wire:click="markAllAsRead"
            class="text-sm font-medium text-white bg-primary-600 px-3 py-1.5 rounded-md shadow hover:bg-primary-700 ">
            ✅ Mark all as read
        </button>
        @endif
    </div>
    </div>

    @forelse ($notifications as $notification)
        @php
            $isUnread = is_null($notification->read_at);
            $textColor = $isUnread ? 'text-primary-900 font-bold' : 'text-gray-600';
            $messageColor = $isUnread ? 'text-green-600' : 'text-gray-500';
            $iconColor = $isUnread ? 'bg-primary-600 text-white' : 'bg-gray-400 text-gray-200';
            $url = $notification->data['url'] ?? null;
        @endphp

        @if ($url)
            <a href="{{ $url }}" class="block" wire:click="markAsRead('{{ $notification->id }}')">
        @else
            <div wire:click="markAsRead('{{ $notification->id }}')">
        @endif

        <div class="flex items-start p-4 border-b hover:bg-primary-50 cursor-pointer hover:translate-x-3 transition-all ease-out hover:scale-125">
            <div class="w-10 h-10 rounded-full shadow flex items-center justify-center text-sm font-bold {{ $iconColor }}">
                {{ strtoupper(substr($notification->data['user'] ?? 'UA', 0, 1)) }}{{ strtoupper(substr($notification->data['user'] ?? 'UA', 1, 1)) }}
            </div>

            <div class="ml-4 flex-1">
                <p class="text-sm {{ $textColor }}">
                    {{ $notification->data['title'] ?? 'Notification' }}
                </p>
                <p class="text-xs {{ $messageColor }} line-clamp-2">
                    {{ $notification->data['message'] ?? 'No message' }}
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    @php
    $created = \Carbon\Carbon::parse($notification->created_at);
@endphp

<span>
    <span class=" text-xs">{{ $created->diffForHumans() }}</span>
    - {{ $created->format(' F j, Y  g:i A - l') }}
</span>
                    {{-- {{ \Carbon\Carbon::parse($notification->created_at)->format('l, F j, Y - g:i A') }} --}}
                         {{-- {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }} --}}
                </p>
            </div>

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
        <p class="text-gray-500">No notifications found.</p>
    @endforelse

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if ({{ auth()->check() ? 'true' : 'false' }}) {
                window.Echo.channel(`notifications.{{ auth()->id() }}`)
                    .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (notification) => {
                        Livewire.emit('refreshNotifications');
                    });
            }
        });
    </script>

</div>
