<?php

namespace App\Filament\Resources\BondResource\Pages;

use App\Filament\Resources\BondResource;
use App\Models\EmployeeInformation;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditBond extends EditRecord
{
    protected static string $resource = BondResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $employee = EmployeeInformation::with('bond')->find($data['user_id']);
        if ($employee) {
            $data['employee'] = $employee->id;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::beginTransaction();
        $record->update([
            'amount' => $data['amount'],
            'bond_certificate_number' => $data['bond_certificate_number'],
            'validity_date_from' => $data['validity_date_from'],
            'validity_date_to' => $data['validity_date_to'],
            'user_id' => $data['employee'],
        ]);

        $employee_null = EmployeeInformation::where('bond_id', $record['id'])->update([
            'bond_id' => null,
        ]);

        $employee_update = EmployeeInformation::find($record['user_id'])->update([
            'bond_id' => $record['id'],
        ]);
        DB::commit();

        return $record;
    }


    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
