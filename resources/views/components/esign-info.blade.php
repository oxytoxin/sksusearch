<div class="{{ $textclass }}"
     style="position: absolute; left: {{ $offsetX }}; top: {{ $offsetY }}; font-size: 7px; line-height: 1;">
    @if($name)
        <p class="whitespace-nowrap">{{ $name }}</p>
    @endif

    @if($datetime)
        <p>{{ $datetime }}</p>
    @endif
    @if($showDescription)
        <p>OIC | Delegated Authority</p>
    @endif
</div>
