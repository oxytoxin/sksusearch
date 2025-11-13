<?php

namespace App\Filament\Resources\EmployeeInformationResource\Pages;

use App\Filament\Resources\EmployeeInformationResource;
use App\Models\Campus;
use App\Models\EmployeeInformation;
use App\Models\Office;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditEmployeeInformation extends EditRecord
{
    protected static string $resource = EmployeeInformationResource::class;


    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $employee = EmployeeInformation::with(['user', 'office'])->find($data['id']);
        if ($employee) {
            $data['email'] = $employee->user->email;
            if (isset($employee->campus) && isset($employee->office)) {
                $data['campus_id'] = $employee->campus_id;
                $data['office_id'] = $employee->office->id;
            } else {
                $data['campus_id'] = "";
            }
        }
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::beginTransaction();
        $user = User::find($record['user_id'])->update([
            'email' => $data['email'],
            'password' => Hash::make(strtolower(str_replace(" ", "", $data['last_name'] . "123"))),
        ]);

        $record->update([
            'first_name' => strtoupper($data['first_name']),
            'last_name' => strtoupper($data['last_name']),
            'full_name' => strtoupper($data['full_name']),
            'address' =>  $data['address'],
            'contact_number' =>  $data['contact_number'],
            'birthday' => $data['birthday'],
            'position_id' => $data['position_id'],
            'office_id' => $data['office_id'],
            'campus_id' => $data['campus_id'],
        ]);
        DB::commit();
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
