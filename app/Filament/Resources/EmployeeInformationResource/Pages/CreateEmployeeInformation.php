<?php

namespace App\Filament\Resources\EmployeeInformationResource\Pages;

use App\Filament\Resources\EmployeeInformationResource;
use App\Models\EmployeeInformation;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


class CreateEmployeeInformation extends CreateRecord
{
    // use Actions;
    protected static string $resource = EmployeeInformationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::firstOrCreate([
            'email' => $data['email'],
        ], [
            'password' => Hash::make(strtolower(str_replace(" ", "", $data['last_name'] . "123"))),
        ]);

        if ($user->employee_information()->exists()) {
            $user->employee_information->update([
                'first_name' => strtoupper($data['first_name']),
                'last_name' => strtoupper($data['last_name']),
                'full_name' => strtoupper($data['full_name']),
                'address' =>  $data['address'],
                'birthday' => $data['birthday'],
                'user_id' => $user['id'],
                'position_id' => $data['position_id'],
                'office_id' => $data['office_id'],
                'campus_id' => $data['campus_id'],
            ]);
        } else {
            $user->employee_information()->create([
                'first_name' => strtoupper($data['first_name']),
                'last_name' => strtoupper($data['last_name']),
                'full_name' => strtoupper($data['full_name']),
                'address' =>  $data['address'],
                'birthday' => $data['birthday'],
                'user_id' => $user['id'],
                'position_id' => $data['position_id'],
                'office_id' => $data['office_id'],
                'campus_id' => $data['campus_id'],
            ]);
        }
        $user->refresh();
        return $user->employee_information;
    }
}
