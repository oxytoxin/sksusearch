<?php

namespace App\Filament\Resources\VoucherSubTypeResource\Pages;

use App\Filament\Resources\VoucherSubTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditVoucherSubType extends EditRecord
{
    protected static string $resource = VoucherSubTypeResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $documents = [];
        $liquidation_report_documents = [];
        if (isset($data['documents'])) {
            $documents = collect($data['documents'])->flatten()->toArray();
            unset($data['documents']);
        }
        if (isset($data['liquidation_report_documents'])) {
            $liquidation_report_documents = collect($data['liquidation_report_documents'])->flatten()->toArray();
            unset($data['liquidation_report_documents']);
        }
        $record->update($data);
        $record->related_documents_list()->updateOrCreate([
            'voucher_sub_type_id' => $record->id
        ], [
            'documents' => $documents,
            'liquidation_report_documents' => $liquidation_report_documents,
        ]);
        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['documents'] = collect($this->record->related_documents_list?->documents)->map(fn ($d) => ['name' => $d]) ?? [];
        $data['liquidation_report_documents'] = collect($this->record->related_documents_list?->liquidation_report_documents)->map(fn ($d) => ['name' => $d]) ?? [];
        return $data;
    }
}
