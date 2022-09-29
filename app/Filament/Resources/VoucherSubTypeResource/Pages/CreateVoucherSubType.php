<?php

namespace App\Filament\Resources\VoucherSubTypeResource\Pages;

use App\Filament\Resources\VoucherSubTypeResource;
use App\Models\VoucherSubType;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateVoucherSubType extends CreateRecord
{
    protected static string $resource = VoucherSubTypeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $documents = $data['documents'];
        unset($data['documents']);
        $subtype = VoucherSubType::create($data);
        $subtype->related_documents_list()->create([
            'documents' => $documents
        ]);
        return $subtype;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
