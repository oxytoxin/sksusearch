<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo/>
        </x-slot>
        @env('local')
            <div class="space-y-2">
                <x-login-link email="jesherpalomaria@sksu.edu.ph" label="Login as user"/>
                <x-login-link email="efdeljohncanlas@sksu.edu.ph" label="Login as accountant"/>
                <x-login-link email="juliealbano@sksu.edu.ph" label="Login as signatory 1"/>
                <x-login-link email="christineabo@sksu.edu.ph" label="Login as signatory 2"/>
                <x-login-link email="desireelegaspi@sksu.edu.ph" label="Login as ICU"/>
                <x-login-link email="samueliwag@sksu.edu.ph" label="Login as Budget"/>
                <x-login-link email="tarhatausman@sksu.edu.ph" label="Login as Cashier"/>
                <x-login-link email="samsonmolao@sksu.edu.ph" label="Login as President"/>
                <x-login-link email="searchlegacyaccountsadmin@sksu.edu.ph" label="Login as legacy accounts"/>
            </div>
        @endenv

        <x-jet-validation-errors class="mb-4"/>

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form class="" method="POST" action="{{ route('login') }}">
            @csrf
            @if (app()->environment('local'))
                <div>
                    <x-jet-label class="font-bold text-white drop-shadow" for="email" value="{{ __('Email') }}"/>
                    <x-jet-input class="block w-full mt-1" id="email" name="email" type="email" :value="old('email')"
                                 required autofocus/>
                </div>

                <div class="mt-4">
                    <x-jet-label class="font-bold text-white drop-shadow" for="password" value="{{ __('Password') }}"/>
                    <x-jet-input class="block w-full mt-1" id="password" name="password" type="password" required
                                 autocomplete="current-password"/>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <label class="flex items-center font-bold text-white drop-shadow" for="remember_me">
                        <x-jet-checkbox
                                class="text-primary-600 focus:outline-none focus:outline-primary-600 focus:ring-0"
                                id="remember_me" name="remember"/>
                        <span class="ml-2 text-sm ">{{ __('Remember me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm underline text-primary-100 hover:text-primary-200"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                </div>

                <div class="flex items-center justify-end mt-1">

                    <x-jet-button
                            class="inline-flex items-center w-full py-3 mt-2 text-xs font-semibold tracking-widest uppercase transition border rounded-md !border-primary-600 !bg-primary-600 from-primary-bg-alt to-secondary-bg hover:!bg-primary-500 hover:text-primary-text active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25">
                        <span class="mx-auto">{{ __('Log in') }}</span>
                    </x-jet-button>

                </div>
                <div class="mt-3">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-primary-600"></div>
                        </div>
                        <div class="relative flex justify-center w-full text-sm">
                            <span class="px-3 py-1 text-white rounded-full bg-primary-600">
                                or
                            </span>
                        </div>
                    </div>

                </div>

            @endif

            <div>
                <a class="inline-flex items-center w-full py-2 mt-2 text-xs font-semibold tracking-widest uppercase transition border rounded-md border-primary-600 bg-primary-600 from-primary-bg-alt to-secondary-bg hover:bg-primary-500 hover:text-primary-text active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25"
                   href="auth/google">
                    <span class="inline-flex m-auto text-center"><img class="inline h-6 mx-0 px-auto"
                                                                      src="https://img.icons8.com/color/48/000000/google-logo.png"/> <span
                                class="pl-2 my-auto font-light text-white text-md">Login with Google</span>
                    </span>
                </a>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
