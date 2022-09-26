@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 bg-primary-100 pt-1  border-b-2 border-primary-100 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition mt-2 rounded-t-lg'
            : 'inline-flex items-center px-4 pt-1 border-b-2 border-transparent mb-2 rounded-b-lg text-sm font-medium leading-5 text-white hover:bg-white hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
