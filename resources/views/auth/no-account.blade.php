<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo/>
        </x-slot>

        <div class="text-center">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>

            <h2 class="text-xl font-bold text-white">Account Not Found</h2>

            <p class="mt-3 text-sm text-white">
                The Google account you used is not yet registered in the S.E.A.R.C.H. system.
                If you are an SKSU employee, you can self-register using your institutional email (<span class="font-semibold">@sksu.edu.ph</span>).
            </p>

            <div class="mt-6 flex items-center justify-center space-x-3">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Back to Login
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Register Now
                </a>
            </div>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>
