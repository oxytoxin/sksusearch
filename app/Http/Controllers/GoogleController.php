<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Employee_information;
use App\Models\EmployeeInformation;
use Carbon\Carbon;

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
            //$userExists = User::where('provider_id', $user->id)->where('email', $user->email)->first();
            
            $finduser = User::where('email', $user->email)->first();
           
            $str =  $user->email;

            //$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@(?gmail|sksu\.edu\.ph$)^";
            //$res = preg_match_all($pattern, $str);
            //if($res >= 1){            
                if($finduser)
                {
                    Auth::login($finduser);
                    return redirect()->route('redirect');
                    
                }else{
                    
                    return redirect()->route('401-error');
                }




                 // if(!$finduser)
                // {
                //             $finduser = new User();
                //             $finduser->email =$user->email;
                //             $finduser->email_verified_at = $user->user['verified_email'] == true ? Carbon::now() : null;
                //             $finduser->save();

                //             $find_employee = new Employee_information();
                //             $find_employee->full_name = $user->name;
                //             $find_employee->first_name = $user->user['given_name'];
                //             $find_employee->last_name = $user->user['family_name'];
                //             // $finduser->email =$user->email;
                //            // $find_employee->email_verified_at = $user->user['verified_email'] == true ? Carbon::now() : null;
                //             $find_employee->provider_id = $user->id;
                //             $find_employee->avatar = $user->avatar;
                //             $find_employee->user_id = $user->id;
                //             $find_employee->save();
                //             Auth::login($find_employee);
                //             return redirect()->route('redirect');
                // }else{
                //     Auth::login($find_employee);
                //     return redirect()->route('redirect');
                // }
           
    
        } catch (Exception $e) {
            
            dd($e);
        }
    }
}