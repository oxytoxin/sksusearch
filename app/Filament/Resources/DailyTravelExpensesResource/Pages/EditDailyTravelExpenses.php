<?php

namespace App\Filament\Resources\DailyTravelExpensesResource\Pages;

use App\Filament\Resources\DailyTravelExpensesResource;
use App\Models\PhilippineRegion;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditDailyTravelExpenses extends EditRecord
{
    protected static string $resource = DailyTravelExpensesResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $dte = PhilippineRegion::with('dte')->find($data['id']);
        if ($dte) {
            $data['philippine_region_id'] = $dte->region_description;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update([
            'amount' => strtoupper($data['amount']),
        ]);

         return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
