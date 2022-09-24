@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-2 py-2 text-sm font-medium text-white rounded-md bg-primary-500 group'
            : 'flex items-center px-2 py-2 text-sm font-medium text-primary-600 rounded-md group hover:bg-primary-100 hover:text-primary-800';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
