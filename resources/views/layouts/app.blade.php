<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @wireUiScripts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts
    @stack('scripts')
</head>

<body class="antialiased">
    <x-jet-banner />

    <div class="min-h-screen bg-primary-100">
        @livewire('navigation-menu')
        
        <!-- Page Content -->
        <main>
            <div x-data="{ opensidebar: false }" x-cloak>
                <!-- Static sidebar for desktop -->
                <div class="hidden md:fixed md:flex md:h-full md:w-64 md:flex-col">
                    <!-- Sidebar component, swap this element with another sidebar if you like -->
                    <div class="flex flex-col flex-1 min-h-0 bg-white shadow-lg">
                        <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">

                            <nav class="flex-1 px-2 mt-5 space-y-1">

                                <x-sidenav-link href="{{ route('requisitioner.dashboard') }}" :active="request()->routeIs('requisitioner.dashboard')">
                                    My Dashboard
                                </x-sidenav-link>
                                <x-sidenav-link href="{{ route('office.dashboard') }}" :active="request()->routeIs('office.dashboard')">
                                    Office Dashboard
                                </x-sidenav-link>
                                <div class="space-y-1" x-data="{ open: false }">
                                    <!-- Current: "bg-primary-100 text-primary-900", Default: "bg-white text-primary-600 hover:bg-primary-50 hover:text-primary-900" -->
                                    <button x-on:click="open=!open" type="button"
                                        class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left bg-white rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                        aria-controls="sub-menu-1" aria-expanded="false">
                                        <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
                                        <svg :class="open ?
                                            'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                                            'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                                            class="" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                                        </svg>
                                        Disbursement Vouchers
                                    </button>
                                    <!-- Expandable link section, show/hide based on state. -->
                                    <div class="space-y-1" id="sub-menu-1" x-show='open'
                                        x-transition:enter='transition ease-out duration-300'
                                        x-transition:enter-start='opacity-0 scale-95'
                                        x-transition:enter-end='opacity-100 scale-100'
                                        x-transition:leave='transition ease-in duration-300'
                                        x-transition:leave-start='opacity-100 scale-100'
                                        x-transition:leave-end='opacity-0 scale-95' class="origin-top-left">
                                        <a href="#"
                                            class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                                            Pending Disbursement Vouchers</a>
                                        <a href="#"
                                            class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                                            Unliquidated Disbursement Vouchers</a>
                                        <a href="#"
                                            class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                                            Closed Disbursement Vouchers</a>
                                    </div>
                                </div>

                                <div class="space-y-1" x-data="{ open: false }">
                                    <button x-on:click="open=!open" type="button"
                                        class="flex items-center w-full py-2 pr-2 text-sm font-medium text-left bg-white rounded-md text-primary-600 group hover:bg-primary-50 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                        aria-controls="sub-menu-1" aria-expanded="false">
                                        <!-- Expanded: "text-primary-400 rotate-90", Collapsed: "text-primary-300" -->
                                        <svg :class="open ?
                                            'rotate-90 flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400' :
                                            'flex-shrink-0 w-5 h-5 mr-2 text-primary-300 transition-colors duration-150 ease-in-out transform group-hover:text-primary-400'"
                                            class="" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
                                        </svg>
                                        Travel Orders
                                    </button>
                                    <!-- Expandable link section, show/hide based on state. -->
                                    <div class="space-y-1" id="sub-menu-1" x-show='open'
                                        x-transition:enter='transition ease-out duration-300'
                                        x-transition:enter-start='opacity-0 scale-95'
                                        x-transition:enter-end='opacity-100 scale-100'
                                        x-transition:leave='transition ease-in duration-300'
                                        x-transition:leave-start='opacity-100 scale-100'
                                        x-transition:leave-end='opacity-0 scale-95' class="origin-top-left">
                                        <a href="{{ route('requisitioner.travel-orders.index') }}"
                                            class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                                            My Travel Orders</a>
                                        @if (in_array(auth()->user()->employee_information->position_id, [
                                            5,
                                            12,
                                            13,
                                            11,
                                            14,
                                            15,
                                            16,
                                            17,
                                            18,
                                            19,
                                            20,
                                            21,
                                            25,
                                        ]))
                                            <a href="{{ route('signatory.travel-orders.index') }}"
                                                class="flex items-center w-full py-2 pl-10 pr-2 text-sm font-medium rounded-md text-primary-600 group hover:bg-primary-100 hover:text-primary-900">
                                                Travel orders to
                                                sign</a>
                                        @endif

                                    </div>
                                </div>
                        </div>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="flex flex-col flex-1 md:pl-64">
                <div class="sticky top-0 z-10 pt-1 pl-1 bg-primary-100 sm:pl-3 sm:pt-3 md:hidden">
                    <button type="button" x-on:click="opensidebar = true"
                        class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-primary-500 hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Open sidebar</span>
                        <!-- Heroicon name: outline/bars-3 -->
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
                <main class="flex-1">
                    <div class="py-6">
                        <div class="max-w-full px-4 mx-auto sm:px-6 md:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
    </div>
    <x-dialog z-index="z-50" blur="md" align="center" />
    </main>
    </div>

    @stack('modals')

    @livewire('notifications')
</body>

</html>
