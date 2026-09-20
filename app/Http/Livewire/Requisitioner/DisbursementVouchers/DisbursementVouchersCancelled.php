<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use App\Models\DisbursementVoucher;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class DisbursementVouchersCancelled extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable, OfficeDashboardActions;

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereForCancellation(true)->whereUserId(auth()->id())->latest();
    }

    protected function getTableColumns()
    {
        return [
            ...$this->officeTableColumns(),
            TextColumn::make('status')->formatStateUsing(fn ($record) => $record->cancelled_at ? 'Cancelled' : 'Pending'),

        ];
    }

    public function getTableActions()
    {
        return [
            ...$this->viewActions(),
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.disbursement-vouchers-cancelled');
    }
}
