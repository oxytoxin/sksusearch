@extends('layouts.verification')
@section('content')
    <div>
        <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet' />
        <div style="margin: auto; width: 50%; padding: 10px; display: flex; justify-content: center; align-items: center;">

            <div style="width: 350px; height: 480px; background: #9ec299; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;"
                class="card">
                <img src="../images/searchlogo.png" alt="" width="50" style="height:auto;display:block;" />
                <h1
                    style="font-family: 'Rubik'; text-align: center; padding: 10px; font-size: 20px; font-weight: bold; color: #0a5200;">
                    SKSU S.E.A.R.C.H</h1>
                <img src="../images/sksulogo.png" alt="" width="50" style="height:auto;display:block;" />


                {{-- <svg class="email" style="width: 8rem; height: 8rem; margin: auto; color: #0a5200;"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                </svg> --}}
                <h3
                    style="font-family: 'mono'; text-align: center; padding: 10px; font-size: 20px; font-weight: bold; color: #0a5200;">
                    Here is your verification code: </h3>
                <p
                    style="font-family: 'Rubik'; font-size: 3rem; text-align: center; text-decoration: underline; color: #0a5200;">
                    {{ $code }}</p>
            </div>
            <div>

            </div>


            {{-- <div class="mt-4">
                <button class="px-2 py-2 text-blue-200 bg-blue-600 rounded">Click to Verify Email</button>
                <p class="mt-4 text-sm">If you’re having trouble clicking the "Verify Email Address" button, copy
                    and
                    paste
                    the URL below
                    into your web browser:
                    <a href="#" class="text-blue-600 underline">http://localhost:8000/email/verify/3/1ab7a09a3</a>
                </p>
            </div> --}}
        </div>
    </div>
@endsection
