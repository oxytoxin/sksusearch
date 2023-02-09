<?php

namespace App\Filament\Resources\VoucherTypeResource\Pages;

use App\Filament\Resources\VoucherTypeResource;
use DB;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoucherType extends EditRecord
{
    protected static string $resource = VoucherTypeResource::class;

    protected function getActions(): array
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
                    $this->redirect(route('filament.resources.voucher-types.index'));
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
