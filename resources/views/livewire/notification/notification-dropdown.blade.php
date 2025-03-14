<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell Button -->
   <!-- Notification Bell Button -->
<button @click="open = !open; if(open) { Livewire.emit('markAllAsRead'); }" class="relative p-2 text-white">
    <i class="fa-solid fa-bell text-xl"></i>

    <!-- Notification Badge -->
    @if ($unreadCount > 0)
        <span class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
            {{ $unreadCount }}
        </span>
    @endif
</button>


    <!-- Notification Dropdown -->
    <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 top-10 w-80 bg-white shadow-2xl rounded-lg overflow-hidden z-50">
        <!-- Notification Header -->
        <div class="px-4 py-2 bg-white border-b flex justify-between items-center">
            <h3 class="text-lg text-primary-600 font-semibold">Notifications</h3>
            <!-- Mark All as Read Button (Optional) -->
            <button wire:click="markAllAsRead" class="text-sm text-primary-600 hover:text-primary-800">
                Mark all as read
            </button>
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse ($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $textColor = $isUnread ? 'text-primary-900 font-bold' : 'text-gray-600';
                    $messageColor = $isUnread ? 'text-green-600' : 'text-gray-500';
                    $iconColor = $isUnread ? 'bg-primary-600 text-white' : 'bg-gray-400 text-gray-200';
                @endphp

                <div wire:click="markAsRead('{{ $notification->id }}')"
                     class="flex items-start p-3 border-b hover:bg-primary-50 transition duration-150 ease-in-out cursor-pointer">

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
                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Read Status Indicator -->
                    @if ($isUnread)
                        <div class="w-2 h-2 bg-red-500 rounded-full self-center ml-2"></div>
                    @endif
                </div>
            @empty
                <!-- Placeholder for No Notifications -->
                <div class="flex flex-col items-center justify-center p-6 text-gray-400">
                    <i class="fa-solid fa-bell-slash text-4xl mb-2"></i>
                    <p class="text-sm">No new notifications</p>
                </div>
            @endforelse
        </div>


        <a href="#" class="block w-full p-3 text-center text-primary-900 hover:text-primary-700 hover:underline">
            View All Notifications
        </a>
    </div>
</div>
