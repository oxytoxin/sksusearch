<?php

namespace App\Filament\Resources\ProvinceResource\Pages;

use App\Filament\Resources\ProvinceResource;
use App\Models\PhilippineProvince;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProvince extends CreateRecord
{
    protected static string $resource = ProvinceResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        $province_code = rand(pow(10, 4 - 1), pow(10, 4) - 1);
        $psgc = $province_code . '00000';

        $province = PhilippineProvince::create([
            'psgc_code' => $psgc,
            'province_description' => $data['province_description'],
            'region_code' => $data['region_code'],
            'province_code' => $province_code
        ]);

        return $province;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
