<x-app-layout>
   <x-slot name="header">
       <h2 class="text-xl font-semibold leading-tight text-gray-800">
           {{ __('PHP INFO') }}
       </h2>
   </x-slot>

    @php
            phpinfo();
    @endphp
</x-app-layout>