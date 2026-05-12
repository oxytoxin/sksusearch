@props([
    'signature' => null,
    'width' => '10rem',
    'maxHeight' => '4rem',
    'top' => null,
    'bottom' => null,
    'left' => '50%',
    'translateX' => '-50%',
    'translateY' => '0',
])

@if($signature)
    <img src="{{ $signature }}" alt="signature"
        class="absolute print:!opacity-100"
        style="
            width: {{ $width }};
            height: auto;
            max-height: {{ $maxHeight }};
            object-fit: contain;
            left: {{ $left }};
            transform: translateX({{ $translateX }}) translateY({{ $translateY }});
            @if($top) top: {{ $top }}; @endif
            @if($bottom) bottom: {{ $bottom }}; @endif
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        ">
@endif
