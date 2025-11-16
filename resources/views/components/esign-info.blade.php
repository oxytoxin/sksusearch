<div class="{{ $textclass }}"
     style="position: absolute; left: {{ $offsetX }}; top: {{ $offsetY }};">
    @if($name)
        <p class="font-semibold">{{ $label }}</p>
        <p class="font-bold">{{ $name }}</p>
    @endif

    @if($datetime)
        <p>{{ \Carbon\Carbon::parse($datetime)->format('Y-M-d | H:i:s') }} GMT+08:00</p>
    @endif
</div>
