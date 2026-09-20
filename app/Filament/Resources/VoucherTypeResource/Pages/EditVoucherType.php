<?php

namespace App\Filament\Resources\VoucherTypeResource\Pages;

use App\Filament\Resources\VoucherTypeResource;
use DB;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoucherType extends EditRecord
{
    protected static string $resource = VoucherTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(function ($record) {
                    DB::beginTransaction();
                    $record->voucher_subtypes->each(function ($st) {
                        $st->related_documents_list()->delete();
                        $st->delete();
                    });
                    $record->delete();
                    DB::commit();
                    Notification::make()->title('Deleted.')->success()->send();
                    $this->redirect(VoucherTypeResource::getUrl('index'));
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
