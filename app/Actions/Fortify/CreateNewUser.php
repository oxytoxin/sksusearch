<?php

namespace App\Actions\Fortify;

use App\Models\EmployeeInformation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input)
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'ends_with:@sksu.edu.ph'],
            'address' => ['nullable', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            'contact_number' => ['nullable', 'string', 'max:255'],
            'campus_id' => ['required', 'exists:campuses,id'],
            'office_id' => ['required', 'exists:offices,id'],
            'position_id' => ['required', 'exists:positions,id'],
        ], [
            'email.ends_with' => 'You must use your institutional email (@sksu.edu.ph).',
            'email.unique' => 'This email is already registered in the system.',
            'email.email' => 'Please enter a valid email address.',
            'campus_id.required' => 'Please select a campus.',
            'office_id.required' => 'Please select an office.',
            'position_id.required' => 'Please select a position.',
        ])->validate();

        $user = User::create([
            'email' => $input['email'],
            'password' => Hash::make(strtolower(str_replace(' ', '', $input['last_name'])) . '123'),
        ]);

        EmployeeInformation::create([
            'first_name' => strtoupper($input['first_name']),
            'last_name' => strtoupper($input['last_name']),
            'full_name' => strtoupper($input['full_name']),
            'address' => $input['address'] ?? null,
            'birthday' => $input['birthday'] ?? null,
            'contact_number' => $input['contact_number'] ?? null,
            'user_id' => $user->id,
            'campus_id' => $input['campus_id'],
            'office_id' => $input['office_id'],
            'position_id' => $input['position_id'],
        ]);

        return $user;
    }
}
