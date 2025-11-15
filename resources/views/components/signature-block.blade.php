@props(['name', 'position' => null, 'signature', 'offsetY' => '-1.2rem', 'size' => '6rem', 'gap' => '1rem'])

<div class="relative inline-block text-center ">

    {{-- Signature Image --}}
    <img src="{{ $signature }}" alt="signature" class="absolute left-1/2 -translate-x-1/2"
        style="
            width: {{ $size }};
            height: {{ $size }};
            bottom: {{ $offsetY }};
        ">

    {{-- Space so signature does not overlap text too low --}}
    <div style="height: {{ $gap }}"></div>

    {{-- NAME --}}
    <p class="font-bold text-sm leading-tight">
        {{ $name }}
    </p>

    {{-- Position (optional) --}}
    @if ($position)
        <p class="text-xs leading-tight">{{ $position }}</p>
    @endif

</div>
