<div class="relative">
    <!-- Notification Button -->
    <button class="p-2 text-white rounded-full hover:bg-primary-600">
        <i class="fa-solid fa-bell text-xl"></i> <!-- Font Awesome Notification Icon -->
    </button>

    <!-- Badge -->
    @if($count > 0)
        <span class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
            {{ $count }}
        </span>
    @endif
</div>
