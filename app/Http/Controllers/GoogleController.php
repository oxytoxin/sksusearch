<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

            $finduser = User::where('email', $user->email)->first();

            $str = $user->email;
            if ($finduser) {
                Auth::login($finduser);

                return redirect()->route('redirect');
            } else {
                return redirect()->route('401-error');
            }
        } catch (Exception $e) {
            dd($e);
        }
    }
}
