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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@1.x.x/dist/cdn.min.js" defer></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/websocket.js'])
    @livewireStyles
    @livewireScripts
    @stack('scripts')
</head>

<body class="antialiased">
    <x-jet-banner />

    <div class="min-h-screen bg-primary-100">
        @livewire('navigation-menu')

        <div>
            <!-- Static sidebar for desktop -->
            <div class="hidden md:fixed md:flex md:h-full md:w-64 md:flex-col">
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <div class="flex flex-col flex-1 min-h-0 bg-white shadow-lg">
                    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
                        <x-sidenav />
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col flex-1 md:pl-64">
            <div class="relative z-40 flex flex-row sm:pl-3 sm:pt-3 md:hidden" x-data="{ opensidebar: false }" x-cloak>
                <div class="fixed inset-0 bg-opacity-75 bg-primary-500" x-show='opensidebar' x-transition:enter='' x-transition:enter-start='' x-transition:enter-end='' x-transition:leave='' x-transition:leave-start='' x-transition:leave-end=''></div>
                <div class="fixed inset-0 z-40 flex" x-show='opensidebar' x-transition:enter='' x-transition:enter-start='' x-transition:enter-end='' x-transition:leave='' x-transition:leave-start='' x-transition:leave-end=''>
                    <div class="relative flex flex-col flex-1 w-full max-w-xs pt-5 pb-4 bg-white" x-show='opensidebar' x-transition:enter='' x-transition:enter-start='' x-transition:enter-end='' x-transition:leave='' x-transition:leave-start='' x-transition:leave-end=''>
                        <div class="absolute top-0 right-0 pt-2 -mr-12">
                            <button class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" type="button" x-on:click="opensidebar = false">
                                <span class="sr-only">Close sidebar</span>
                                <!-- Heroicon name: outline/x-mark -->
                                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-show='opensidebar' x-transition:enter='transition ease-out duration-1000' x-transition:enter-start='transform rotate-180 opacity-80' x-transition:enter-end='transform rotate-0 opacity-100'>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex" :class="opensidebar == true ? 'bg-white' : 'bg-transparent'" x-show='opensidebar' x-transition:enter='transition ease-out duration-500' x-transition:enter-start='' x-transition:enter-end='' x-transition:leave='' x-transition:leave-start='' x-transition:leave-end=''>
                            <x-sidenav />
                        </div>
                        <div class="flex-shrink-0 w-14" aria-hidden="true">
                            <!-- Dummy element to force sidebar to shrink to fit close icon -->
                        </div>
                    </div>
                </div>
                <button class="px-4 py-3 text-gray-600 border-r border-primary-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden" type="button" x-on:click="opensidebar = true">
                    <span class="sr-only">Open sidebar</span>
                    <!-- Heroicon name: outline/bars-3-bottom-left -->
                    <svg class="w-4 h-auto text-primary-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-show='opensidebar == false' x-transition:enter='transition ease-out duration-1000' x-transition:enter-start='transform rotate-180 opacity-80' x-transition:enter-end='transform rotate-0 opacity-100'>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
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
    @stack('modals')
    <script>
        function printOutData(data, title) {
            var mywindow = window.open('', title, 'height=1000,width=1000');
            mywindow.document.write('<html><head>');
            mywindow.document.write('<title>' + title + '</title>');
            mywindow.document.write(`<link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}" />`);
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.document.close();
            mywindow.focus();
            setTimeout(() => {
                mywindow.print();
            }, 1000);
            return false;
        }
    </script>
</body>
@livewire('notifications')

</html>
