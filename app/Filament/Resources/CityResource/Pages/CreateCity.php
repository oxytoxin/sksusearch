<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use App\Models\PhilippineCity;
use App\Models\PhilippineProvince;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCity extends CreateRecord
{
    protected static string $resource = CityResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $province_code = PhilippineProvince::where('province_code', $data['province_code'])->first();
        $region_code = $province_code->region_code;
        $city_code = rand(pow(10, 4 - 1), pow(10, 4) - 1);
        $psgc = $city_code . '00000';

        $city = PhilippineCity::create([
            'psgc_code' => $psgc,
            'city_municipality_description' => $data['city_municipality_description'],
            'region_description' =>  $region_code,
            'province_code' => $data['province_code'],
            'city_municipality_code' => $city_code,
        ]);

        return $city;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
