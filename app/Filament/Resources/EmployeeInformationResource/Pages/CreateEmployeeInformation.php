<?php

namespace App\Filament\Resources\EmployeeInformationResource\Pages;

use App\Filament\Resources\EmployeeInformationResource;
use App\Models\EmployeeInformation;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateEmployeeInformation extends CreateRecord
{
    protected static string $resource = EmployeeInformationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make(strtolower(str_replace(" ", "", $data['last_name'] . "123"))),
        ]);


        $employee = EmployeeInformation::create([
            'first_name' => strtoupper($data['first_name']),
            'last_name' => strtoupper($data['last_name']),
            'full_name' => strtoupper($data['full_name']),
            'address' =>  $data['address'],
            'birthday' => $data['birthday'],
            'user_id' => $user['id'],
            'role_id' => $data['role_id'],
            'position_id' => $data['position_id'],
            'office_id' => $data['office_id'],

        ]);
        return $employee;
    }
}
