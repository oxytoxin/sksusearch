<?php

namespace App\Filament\Resources\BondResource\Pages;

use App\Filament\Resources\BondResource;
use App\Models\Bond;
use App\Models\EmployeeInformation;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateBond extends CreateRecord
{
    protected static string $resource = BondResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();
        $bond = Bond::create([
            'amount' => $data['amount'],
            'bond_certificate_number' => $data['bond_certificate_number'],
            'validity_date_from' => $data['validity_date_from'],
            'validity_date_to' => $data['validity_date_to'],
            'user_id' =>  $data['employee'],
        ]);

        $user = EmployeeInformation::find($data['employee'])->update([
            'bond_id' => $bond['id'],
        ]);
        DB::commit();

        return $bond;
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
