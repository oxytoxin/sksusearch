<div class="{{ $textclass }}"
     style="position: absolute; left: {{ $offsetX }}; top: {{ $offsetY }}; font-size: 10px; line-height: 1;">
    @if($name)
        <p class="font-bold">{{ $name }}</p>
    @endif

    @if($datetime)
        <p>{{ $datetime }}</p>
    @endif
    <p>OIC | Delegated Authority</p>
</div>
