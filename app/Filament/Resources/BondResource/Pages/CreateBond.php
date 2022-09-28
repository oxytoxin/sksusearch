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
            'validity_date' => $data['validity_date'],
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
