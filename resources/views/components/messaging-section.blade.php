<div class="flex mx-auto w-full justify-center">

    <!-- Main Content Slot -->
    {{ $slot }}

    <!-- Additional Slot for Messages -->
    @isset($messages)
        {{ $messages }}
    @endisset
</div>
