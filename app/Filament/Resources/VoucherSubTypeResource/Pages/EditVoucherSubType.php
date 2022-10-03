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
        if (isset($data['documents'])) {
            $documents = $data['documents'];
            unset($data['documents']);
        }
        $record->update($data);
        $record->related_documents_list->first()->update([
            'documents' => $documents
        ]);
        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['documents'])) {
            $data['documents'] = $this->record->related_documents_list->documents;
        }
        return $data;
    }
}
